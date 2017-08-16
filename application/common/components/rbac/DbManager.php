<?php

namespace app\common\components\rbac;

use think\Cache;
use think\Loader;
use think\Model;
use think\Log;
use think\Db;
use think\db\Connection;
use think\Config;
use think\exception;

/**
 * DbManager represents an authorization manager that stores authorization information in database.
 *
 * The database connection is specified by [[db]]. The database schema could be initialized by applying migration:
 *
 * ```
 * yii migrate --migrationPath=@yii/rbac/migrations/
 * ```
 *
 * If you don't want to use migration and need SQL instead, files for all databases are in migrations directory.
 *
 * You may change the names of the tables used to store the authorization and rule data by setting [[itemTable]],
 * [[itemChildTable]], [[assignmentTable]] and [[ruleTable]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class DbManager extends BaseManager
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     * After the DbManager object is created, if you want to change this property, you should only assign it
     * with a DB connection object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $db = 'db';
    /**
     * @var \app\common\components\rbac\AuthItem string the name of the table storing authorization items. Defaults to "auth_item".
     */
    public $itemTable = 'app\common\components\rbac\AuthItem';
    /**
     * @var \app\common\components\rbac\AuthItemChild string the name of the table storing authorization item hierarchy. Defaults to "auth_item_child".
     */
    public $itemChildTable = 'app\common\components\rbac\AuthItemChild';
    /**
     * @var \app\common\components\rbac\AuthAssignment string the name of the table storing authorization item assignments. Defaults to "auth_assignment".
     */
    public $assignmentTable = 'app\common\components\rbac\AuthAssignment';
    /**
     * @var \app\common\components\rbac\AuthRule string the name of the table storing rules. Defaults to "auth_rule".
     */
    public $ruleTable = 'app\common\components\rbac\AuthRule';
    /**
     * @var Cache|array|string the cache used to improve RBAC performance. This can be one of the following:
     *
     * - an application component ID (e.g. `cache`)
     * - a configuration array
     * - a [[\yii\caching\Cache]] object
     *
     * When this is not set, it means caching is not enabled.
     *
     * Note that by enabling RBAC cache, all auth items, rules and auth item parent-child relationships will
     * be cached and loaded into memory. This will improve the performance of RBAC permission check. However,
     * it does require extra memory and as a result may not be appropriate if your RBAC system contains too many
     * auth items. You should seek other RBAC implementations (e.g. RBAC based on Redis storage) in this case.
     *
     * Also note that if you modify RBAC items, rules or parent-child relationships from outside of this component,
     * you have to manually call [[invalidateCache()]] to ensure data consistency.
     *
     * @since 2.0.3
     */
    public $cache;
    /**
     * @var string the key used to store RBAC data in cache
     * @see cache
     * @since 2.0.3
     */
    public $cacheKey = 'rbac';

    /**
     * @var Item[] all auth items (name => Item)
     */
    protected $items;
    /**
     * @var Rule[] all auth rules (name => Rule)
     */
    protected $rules;
    /**
     * @var array auth item parent-child relationships (childName => list of parents)
     */
    protected $parents;

    public $timeFormat = 'Y-m-d H:i:s';


    /**
     * @return \app\common\components\rbac\AuthManager
     */
    private static $_instance;

    /**
     * @return \app\common\components\rbac\DbManager
     */
    public static function getInstance(){
        if (!self::$_instance){
            self::$_instance = new DbManager();
        }
        return self::$_instance;
    }

    protected function __construct()
    {
        $this->init();
    }

    /**
     * Initializes the application component.
     * This method overrides the parent implementation by establishing the database connection.
     */
    public function init()
    {
        $this->db = Db::connect(Config::get('database'));
        if ($this->cacheKey) {
            $this->cacheKey = md5($this->cacheKey);
        }
        if ($this->itemTable) {
            $this->itemTable = Loader::model($this->itemTable);
        }
        if ($this->itemChildTable) {
            $this->itemChildTable = Loader::model($this->itemChildTable);
        }
        if ($this->assignmentTable) {
            $this->assignmentTable = Loader::model($this->assignmentTable);
        }
        if ($this->ruleTable) {
            $this->ruleTable = Loader::model($this->ruleTable);
        }
        if ($this->cache === null) {
            $this->cache = new Cache();
        }
    }

    /**
     * @inheritdoc
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        $assignments = $this->getAssignments($userId);
        $this->loadFromCache();
        if ($this->items !== null) {
            return $this->checkAccessFromCache($userId, $permissionName, $params, $assignments);
        } else {
            return $this->checkAccessRecursive($userId, $permissionName, $params, $assignments);
        }
    }

    /**
     * Performs access check for the specified user based on the data loaded from cache.
     * This method is internally called by [[checkAccess()]] when [[cache]] is enabled.
     * @param string|integer $user the user ID. This should can be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param string $itemName the name of the operation that need access check
     * @param array $params name-value pairs that would be passed to rules associated
     * with the tasks and roles assigned to the user. A param with name 'user' is added to this array,
     * which holds the value of `$userId`.
     * @param Assignment[] $assignments the assignments to the specified user
     * @return boolean whether the operations can be performed by the user.
     * @since 2.0.3
     */
    protected function checkAccessFromCache($user, $itemName, $params, $assignments)
    {
        if (!isset($this->items[$itemName])) {
            return false;
        }

        $item = $this->items[$itemName];

        Log::record($item instanceof Role ? "Checking role: $itemName" : "Checking permission: $itemName", __METHOD__);

        if (!$this->executeRule($user, $item, $params)) {
            return false;
        }

        if (isset($assignments[$itemName]) || in_array($itemName, $this->defaultRoles)) {
            return true;
        }

        if (!empty($this->parents[$itemName])) {
            foreach ($this->parents[$itemName] as $parent) {
                if ($this->checkAccessFromCache($user, $parent, $params, $assignments)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Performs access check for the specified user.
     * This method is internally called by [[checkAccess()]].
     * @param string|integer $user the user ID. This should can be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param string $itemName the name of the operation that need access check
     * @param array $params name-value pairs that would be passed to rules associated
     * with the tasks and roles assigned to the user. A param with name 'user' is added to this array,
     * which holds the value of `$userId`.
     * @param Assignment[] $assignments the assignments to the specified user
     * @return boolean whether the operations can be performed by the user.
     */
    protected function checkAccessRecursive($user, $itemName, $params, $assignments)
    {
        if (($item = $this->getItem($itemName)) === null) {
            return false;
        }

        Log::record($item instanceof Role ? "Checking role: $itemName" : "Checking permission: $itemName", __METHOD__);

        if (!$this->executeRule($user, $item, $params)) {
            return false;
        }

        if (isset($assignments[$itemName]) || in_array($itemName, $this->defaultRoles)) {
            return true;
        }

        $query = $this->itemChildTable->where(['child' => (string) $itemName])->field('parent')->select();
        $dataProvider = $this->toArray($query);
        foreach ($dataProvider as $parent) {
            if ($this->checkAccessRecursive($user, $parent, $params, $assignments)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function getItem($name)
    {
        if (empty($name)) {
            return null;
        }

        if (!empty($this->items[$name])) {
            return $this->items[$name];
        }

        $query = $this->itemTable->where(['name' => (string)$name])->field('parent')->find();
        $row = $this->toArray($query);

        if ($row === false) {
            return null;
        }

        if (!isset($row['data']) || ($data = @unserialize($row['data'])) === false) {
            $row['data'] = null;
        }

        return $this->populateItem($row);
    }

    /**
     * Returns a value indicating whether the database supports cascading update and delete.
     * The default implementation will return false for SQLite database and true for all other databases.
     * @return boolean whether the database supports cascading update and delete.
     */
    protected function supportsCascadeUpdate()
    {
        $configs = $this->db->getConfig();
        return strncmp($configs['type'], 'sqlite', 6) !== 0;
    }

    /**
     * @inheritdoc
     */
    protected function addItem($item)
    {
        $time = time();
        if (property_exists($this,'timeFormat')){
            $time = date($this->timeFormat) ? date($this->timeFormat) : time();
        }
        if ($item->createdAt === null) {
            $item->createdAt = $time;
        }
        if ($item->updatedAt === null) {
            $item->updatedAt = $time;
        }
        $data = [
            'name' => $item->name,
            'type' => $item->type,
            'description' => $item->description,
            'rule_name' => $item->ruleName,
            'data' => $item->data === null ? null : serialize($item->data),
            'created_at' => $item->createdAt,
            'updated_at' => $item->updatedAt,
        ];
        $model = $this->itemTable;
        $model::create($data);

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function removeItem($item)
    {
        if (!$this->supportsCascadeUpdate()) {
            $this->itemChildTable->where(['parent'=>$item->name])->whereOr(['child'=>$item->name])->delete();
            $this->assignmentTable->where(['item_name'=>$item->name])->delete();
        }

        $this->itemTable->where(['name'=>$item->name])->delete();

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function updateItem($name, $item)
    {
        if ($item->name !== $name && !$this->supportsCascadeUpdate()) {
            $model = $this->itemChildTable;
            $model::update(['item_name'=>$item->name],['parent' => $name]);
            $model::update(['child' => $item->name],['child' => $name]);

            $model = $this->assignmentTable;
            $model::update(['item_name' => $item->name],['item_name' => $name]);
        }

        $item->updatedAt = time();
        if (property_exists($this,'timeFormat')){
            $item->updatedAt = date($this->timeFormat) ? date($this->timeFormat) : time();
        }

        $model = $this->itemTable;
        $model::update([
            [
                'name' => $item->name,
                'description' => $item->description,
                'rule_name' => $item->ruleName,
                'data' => $item->data === null ? null : serialize($item->data),
                'updated_at' => $item->updatedAt,
            ]
        ],['name' => $name]);

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function addRule($rule)
    {
        $time = time();
        if (property_exists($this,'timeFormat')){
            $time = date($this->timeFormat) ? date($this->timeFormat) : time();
        }
        if ($rule->createdAt === null) {
            $rule->createdAt = $time;
        }
        if ($rule->updatedAt === null) {
            $rule->updatedAt = $time;
        }
        $model = $this->ruleTable;
        $model::create([
            'name' => $rule->name,
            'data' => serialize($rule),
            'created_at' => $rule->createdAt,
            'updated_at' => $rule->updatedAt,
        ]);

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function updateRule($name, $rule)
    {
        if ($rule->name !== $name && !$this->supportsCascadeUpdate()) {
            $model = $this->itemTable;
            $model::update(['rule_name' => $rule->name], ['rule_name' => $name]);
        }

        $rule->updatedAt = time();
        if (property_exists($this,'timeFormat')){
            $rule->updatedAt = date($this->timeFormat) ? date($this->timeFormat) : time();
        }

        $model = $this->ruleTable;
        $model::update([
            'name' => $rule->name,
            'data' => serialize($rule),
            'updated_at' => $rule->updatedAt,
        ], [
            'name' => $name,
        ]);

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function removeRule($rule)
    {
        if (!$this->supportsCascadeUpdate()) {
            $model = $this->itemTable;
            $model::update(['rule_name' => null], ['rule_name' => $rule->name]);
        }

        $this->ruleTable->where(['name'=>$rule->name])->delete();

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function getItems($type)
    {
        $query = $this->itemTable->where(['type' => $type])->select();
        $dataProvider = $this->toArray($query);

        $items = [];
        foreach ($dataProvider as $row) {
            $items[$row['name']] = $this->populateItem($row);
        }

        return $items;
    }

    /**
     * Populates an auth item with the data fetched from database
     * @param array $row the data from the auth item table
     * @return Item the populated auth item instance (either Role or Permission)
     */
    protected function populateItem($row)
    {
        $class = $row['type'] == Item::TYPE_PERMISSION ? Permission::class : Role::class;

        if (!isset($row['data']) || ($data = @unserialize($row['data'])) === false) {
            $data = null;
        }

        return new $class([
            'name' => $row['name'],
            'type' => $row['type'],
            'description' => $row['description'],
            'ruleName' => $row['rule_name'],
            'data' => $data,
            'createdAt' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getRolesByUser($userId)
    {
        if (!isset($userId) || $userId === '') {
            return [];
        }

        $model = $this->itemTable;
        $query = $this->assignmentTable->alias('t')
            ->join([$model::tableName()=>'a'],'t.item_name = a.name')
            ->where(['t.user_id' => (string) $userId])
            ->where(['a.type' => Item::TYPE_ROLE])
            ->field('a.*')
            ->select();

        $dataProvider = $this->toArray($query);

        $roles = [];
        foreach ($dataProvider as $row) {
            $roles[$row['name']] = $this->populateItem($row);
        }
        return $roles;
    }

    /**
     * @param string $roleName
     * @return array
     */
    public function getPermissionsByRole($roleName)
    {
        $childrenList = $this->getChildrenList();
        $result = [];
        $this->getChildrenRecursive($roleName, $childrenList, $result);
        if (empty($result)) {
            return [];
        }

        $query = $this->itemTable
            ->where(['type' => Item::TYPE_PERMISSION])
            ->where('name','in', array_keys($result))
            ->select();

        $dataProvider = $this->toArray($query);
        $permissions = [];
        foreach ($dataProvider as $row) {
            $permissions[$row['name']] = $this->populateItem($row);
        }
        return $permissions;
    }

    /**
     * @param int|string $userId
     * @return array
     */
    public function getPermissionsByUser($userId)
    {
        if (empty($userId)) {
            return [];
        }

        $directPermission = $this->getDirectPermissionsByUser($userId);
        $inheritedPermission = $this->getInheritedPermissionsByUser($userId);

        return array_merge($directPermission, $inheritedPermission);
    }

    /**
     * Returns all permissions that are directly assigned to user.
     * @param string|integer $userId the user ID (see [[\yii\web\User::id]])
     * @return Permission[] all direct permissions that the user has. The array is indexed by the permission names.
     * @since 2.0.7
     */
    protected function getDirectPermissionsByUser($userId)
    {

        $model = $this->itemTable;
        $query = $this->assignmentTable->alias('t')
            ->join([$model::tableName()=>'a'],'t.item_name = a.name')
            ->where(['t.user_id' => (string) $userId])
            ->where(['a.type' => Item::TYPE_PERMISSION])
            ->field('a.*')
            ->select();

        $dataProvider = $this->toArray($query);

        $permissions = [];
        foreach ($dataProvider as $row) {
            $permissions[$row['name']] = $this->populateItem($row);
        }
        return $permissions;
    }

    /**
     * Returns all permissions that the user inherits from the roles assigned to him.
     * @param string|integer $userId the user ID (see [[\yii\web\User::id]])
     * @return Permission[] all inherited permissions that the user has. The array is indexed by the permission names.
     * @since 2.0.7
     */
    protected function getInheritedPermissionsByUser($userId)
    {
        $query = $this->assignmentTable->where(['user_id' => (string) $userId])->field('item_name')->select();
        $dataProvider = $this->toArray($query);

        $childrenList = $this->getChildrenList();
        $result = [];
        foreach ($dataProvider as $row) {
            $roleName = $row['item_name'];
            $this->getChildrenRecursive($roleName, $childrenList, $result);
        }

        if (empty($result)) {
            return [];
        }


        $query = $this->itemTable
            ->where(['type' => Item::TYPE_PERMISSION])
            ->where('name','in', array_keys($result))
            ->select();

        $dataProvider = $this->toArray($query);

        $permissions = [];
        foreach ($dataProvider as $row) {
            $permissions[$row['name']] = $this->populateItem($row);
        }
        return $permissions;
    }

    /**
     * Returns the children for every parent.
     * @return array the children list. Each array key is a parent item name,
     * and the corresponding array value is a list of child item names.
     */
    protected function getChildrenList()
    {

        $query = $this->itemChildTable->select();

        $dataProvider = $this->toArray($query);
        $parents = [];
        foreach ($dataProvider as $row) {
            $parents[$row['parent']][] = $row['child'];
        }
        return $parents;
    }

    /**
     * Recursively finds all children and grand children of the specified item.
     * @param string $name the name of the item whose children are to be looked for.
     * @param array $childrenList the child list built via [[getChildrenList()]]
     * @param array $result the children and grand children (in array keys)
     */
    protected function getChildrenRecursive($name, $childrenList, &$result)
    {
        if (isset($childrenList[$name])) {
            foreach ($childrenList[$name] as $child) {
                $result[$child] = true;
                $this->getChildrenRecursive($child, $childrenList, $result);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getRule($name)
    {
        if ($this->rules !== null) {
            return isset($this->rules[$name]) ? $this->rules[$name] : null;
        }

        $query = $this->ruleTable->where(['name' => $name])->field('data')->find();
        $dataProvider = $this->toArray($query);

        return empty($dataProvider) ? null : unserialize($dataProvider['data']);
    }

    /**
     * @inheritdoc
     */
    public function getRules()
    {
        if ($this->rules !== null) {
            return $this->rules;
        }

        $query = $this->ruleTable->select();
        $dataProvider = $this->toArray($query);

        $rules = [];
        foreach ($dataProvider as $row) {
            $rules[$row['name']] = unserialize($row['data']);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getAssignment($roleName, $userId)
    {
        if (empty($userId)) {
            return null;
        }

        $query = $this->assignmentTable
            ->where(['user_id' => (string) $userId])
            ->where(['item_name' => $roleName])
            ->find();
        $dataProvider = $this->toArray($query);

        if (empty($dataProvider)) {
            return null;
        }

        return new Assignment([
            'userId' => $dataProvider['user_id'],
            'roleName' => $dataProvider['item_name'],
            'createdAt' => $dataProvider['created_at'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if (empty($userId)) {
            return [];
        }

        $query = $this->assignmentTable->where(['user_id' => (string) $userId])->select();
        $dataProvider = $this->toArray($query);

        $assignments = [];
        foreach ($dataProvider as $row) {
            $assignments[$row['item_name']] = new Assignment([
                'userId' => $row['user_id'],
                'roleName' => $row['item_name'],
                'createdAt' => $row['created_at'],
            ]);
        }

        return $assignments;
    }

    /**
     * @inheritdoc
     * @since 2.0.8
     */
    public function canAddChild($parent, $child)
    {
        return !$this->detectLoop($parent, $child);
    }

    /**
     * @inheritdoc
     */
    public function addChild($parent, $child)
    {
        if ($parent->getAttr('name') === $child->getAttr('name')) {
            throw new Exception("Cannot add '{$parent->getAttr('name')}' as a child of itself.");
        }

        if ($parent instanceof Permission && $child instanceof Role) {
            throw new Exception('Cannot add a role as a child of a permission.');
        }

        if ($this->detectLoop($parent, $child)) {
            throw new Exception("Cannot add '{$child->getAttr('name')}' as a child of '{$parent->getAttr('name')}'. A loop has been detected.");
        }

        $model = $this->itemChildTable;
        $model::create(['parent' => $parent->getAttr('name'), 'child' => $child->getAttr('name')]);

        $this->invalidateCache();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function removeChild($parent, $child)
    {
        $result = $this->itemChildTable->where(['parent' => $parent->name, 'child' => $child->name])->delete() > 0;

        $this->invalidateCache();

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function removeChildren($parent)
    {
        $result = $this->itemChildTable->where(['parent' => $parent->name])->delete() > 0;

        $this->invalidateCache();

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hasChild($parent, $child)
    {
        $query = $this->itemChildTable->where(['parent' => $parent->name, 'child' => $child->name])->find();
        return !empty($query);
    }

    /**
     * @inheritdoc
     */
    public function getChildren($name)
    {
        $model = $this->itemTable;
        $query = $this->itemChildTable->alias('t')
            ->join([$model::tableName()=>'a'],'t.parent = a.name')
            ->where(['t.parent' => $name])
            ->select();

        $dataProvider = $this->toArray($query);
        $children = [];
        foreach ($dataProvider as $row) {
            $children[$row['name']] = $this->populateItem($row);
        }

        return $children;
    }

    /**
     * Checks whether there is a loop in the authorization item hierarchy.
     * @param Item $parent the parent item
     * @param Item $child the child item to be added to the hierarchy
     * @return boolean whether a loop exists
     */
    protected function detectLoop($parent, $child)
    {
        if ( !$parent || !$child){
            return true;
        }
        if ($child->getAttr('name') === $parent->getAttr('name')) {
            return true;
        }
        foreach ($this->getChildren($child->getAttr('name')) as $grandchild) {
            if ($this->detectLoop($parent, $grandchild)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function assign($role, $userId)
    {
        $time = time();
        if (property_exists($this,'timeFormat')){
            $time = date($this->timeFormat) ? date($this->timeFormat) : time();
        }
        $assignment = new Assignment([
            'userId' => $userId,
            'roleName' => $role->name,
            'createdAt' => $time,
        ]);

        $data = [
            'user_id' => $userId,
            'item_name' => $role->name,
            'created_at' => $time,
        ];
        $model = $this->assignmentTable;
        $model::create($data);

        return $assignment;
    }

    /**
     * @inheritdoc
     */
    public function revoke($role, $userId)
    {
        if (empty($userId)) {
            return false;
        }

        return $this->assignmentTable->where(['user_id' => (string) $userId, 'item_name' => $role->getAttr('name')])->delete() > 0;
    }

    /**
     * @inheritdoc
     */
    public function revokeAll($userId)
    {
        if (empty($userId)) {
            return false;
        }

        return $this->assignmentTable->where(['user_id' => (string) $userId])->delete() > 0;
    }

    /**
     * @inheritdoc
     */
    public function removeAll()
    {
        $this->removeAllAssignments();

        $model = $this->itemChildTable;
        $sql = 'delete '.' from '.$model::tableName();
        $this->db->execute($sql);

        $model = $this->itemTable;
        $sql = 'delete '.' from '.$model::tableName();
        $this->db->execute($sql);

        $model = $this->ruleTable;
        $sql = 'delete '.' from '.$model::tableName();
        $this->db->execute($sql);

        $this->invalidateCache();
    }

    /**
     * @inheritdoc
     */
    public function removeAllPermissions()
    {
        $this->removeAllItems(Item::TYPE_PERMISSION);
    }

    /**
     * @inheritdoc
     */
    public function removeAllRoles()
    {
        $this->removeAllItems(Item::TYPE_ROLE);
    }

    /**
     * Removes all auth items of the specified type.
     * @param integer $type the auth item type (either Item::TYPE_PERMISSION or Item::TYPE_ROLE)
     */
    protected function removeAllItems($type)
    {
        if (!$this->supportsCascadeUpdate()) {
            $query = $this->itemTable->where(['type' => $type])->field('name')->select();
            $names = [];
            $dataProvider = $this->toArray($query);

            if (!empty($query)) {
                if (!empty($dataProvider)){
                    foreach ($dataProvider as $value){
                        $names[] = $value['name'];
                    }
                }
            }else{
                return;
            }
            $key = $type == Item::TYPE_PERMISSION ? 'child' : 'parent';
            $this->itemChildTable->where($key,'in',$names)->delete();
            $this->assignmentTable->where('item_name','in',$names)->delete();
        }
        $this->itemTable->where(['type'=>$type])->delete();

        $this->invalidateCache();
    }

    /**
     * @inheritdoc
     */
    public function removeAllRules()
    {
        if (!$this->supportsCascadeUpdate()) {
            $model = $this->itemTable;
            $model::update(['rule_name' => null]);
        }


        $model = $this->ruleTable;
        $sql = 'delete '.' from '.$model::tableName();
        $this->db->execute($sql);

        $this->invalidateCache();
    }

    /**
     * @inheritdoc
     */
    public function removeAllAssignments()
    {
        $model = $this->assignmentTable;
        $sql = 'delete '.' from '.$model::tableName();
        $this->db->execute($sql);
    }

    public function invalidateCache()
    {
        $cache = $this->cache;
        if ($cache !== null) {
            $cache::rm($this->cacheKey);
            $this->items = null;
            $this->rules = null;
            $this->parents = null;
        }
    }

    public function loadFromCache()
    {
        $cache = $this->cache;
        if ($this->items !== null || !$cache instanceof Cache) {
            return;
        }

        $data = $this->cache->get($this->cacheKey);
        if (is_array($data) && isset($data[0], $data[1], $data[2])) {
            list ($this->items, $this->rules, $this->parents) = $data;
            return;
        }

        $query = $this->itemTable->select();
        $dataProvider = $this->toArray($query);
        $this->items = [];
        foreach ($dataProvider as $row) {
            $this->items[$row['name']] = $this->populateItem($row);
        }

        $query = $this->ruleTable->select();
        $dataProvider = $this->toArray($query);
        $this->rules = [];
        foreach ($dataProvider as $row) {
            $this->rules[$row['name']] = unserialize($row['data']);
        }

        $query = $this->itemChildTable->select();
        $dataProvider = $this->toArray($query);
        $this->parents = [];
        foreach ($dataProvider as $row) {
            if (isset($this->items[$row['child']])) {
                $this->parents[$row['child']][] = $row['parent'];
            }
        }

        $this->cache->set($this->cacheKey, [$this->items, $this->rules, $this->parents]);
    }

    /**
     * Returns all role assignment information for the specified role.
     * @param string $roleName
     * @return Assignment[] the assignments. An empty array will be
     * returned if role is not assigned to any user.
     * @since 2.0.7
     */
    public function getUserIdsByRole($roleName)
    {
        if (empty($roleName) || !is_string($roleName)) {
            return [];
        }

        $ret = [];
        $query = $this->assignmentTable->where(['item_name' => $roleName])->field('user_id')->select();
        $dataProvider = $this->toArray($query);
        if (!empty($dataProvider)){
            foreach ($dataProvider as $value){
                $ret[] = $value['user_id'];
            }
        }
        return $ret;
    }

    /**
     * @param array |\think\Model |mixed  $resultSet
     * @return array
     */
    public function toArray($resultSet){
        $ret = [];
        if (empty($resultSet) || !(is_array($resultSet) || is_object($resultSet))){
            return $ret;
        }
        $item = current($resultSet);
        if ($resultSet instanceof Model) {
            $ret = $resultSet->toArray();
        } elseif($item instanceof Model){
            foreach ($resultSet as $value){
                $ret[] = $value->toArray();
            }
        } else {
            $ret = (array)$resultSet;
        }
        return $ret;
    }
}

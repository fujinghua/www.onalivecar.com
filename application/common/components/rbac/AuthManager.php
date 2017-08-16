<?php

namespace app\common\components\rbac;

use app\common\components\rbac\DbManager;

class AuthManager extends DbManager
{

    //默认角色
    private $_defaultRoles = ['0','1'];

    /**
     * @return \app\common\components\rbac\AuthManager
     */
    private static $_instance;

    /**
     * @return \app\common\components\rbac\AuthManager
     */
    public static function getInstance(){
        if (!self::$_instance){
            self::$_instance = new AuthManager();
        }
        return self::$_instance;
    }

    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * Memory cache of assignments
     * @var array
     */
    private $_assignments = [];
    private $_childrenList;
    private $_roleName;


    /**
     * @return array
     */
    public function getDefaultRoles()
    {
        return $this->defaultRoles;
    }


    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if (!isset($this->_assignments[$userId])) {
            $this->_assignments[$userId] = parent::getAssignments($userId);
        }
        return $this->_assignments[$userId];
    }

    /**
     * @inheritdoc
     */
    public function getRoleName($userId)
    {
        if (!isset($this->_assignments[$userId])) {
            $this->_assignments[$userId] = parent::getAssignments($userId);
        }
        foreach ($this->_assignments[$userId] as $key=>$value){
            if (is_object($value)){
                if (property_exists($value,'roleName')){
                    $this->_roleName = $value->roleName;
                }
            }
        }
        return $this->_roleName;
    }

    /**
     * @inheritdoc
     */
    protected function getChildrenList()
    {
        if ($this->_childrenList === null) {
            $this->_childrenList = parent::getChildrenList();
        }
        return $this->_childrenList;
    }
}

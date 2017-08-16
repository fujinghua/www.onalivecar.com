<?php

namespace app\common\components;

use app\manage\model\Menu;
use app\common\components\Configs;

/**
 * MenuHelper used to generate menu depend of user role.
 * Usage
 * 
 * ~~~
 * use app\common\components\MenuHelper;
 *
 * To reformat returned, provide callback to method.
 * 
 * ~~~
 * $callback = function ($menu) {
 *    $data = eval($menu['data']);
 *    return [
 *        'label' => $menu['name'],
 *        'url' => [$menu['route']],
 *        'options' => $data,
 *        'items' => $menu['children']
 *        ]
 *    ]
 * }
 *
 * $items = MenuHelper::getAssignedMenu($userId, null, $callback);
 * ~~~
 */
class MenuHelper
{
    /**
     * @var string //路由前缀
     */
    private static $_prefixUrl = '';

    /**
     * @var string //路由虚拟后缀
     */
    private static $_suffix = '.html';

    /**
     * @var string //路由默认参数
     */
    private static $_query = '';

    /**
     * Use to get assigned menu of user.
     * callback should have format like
     *
     * ~~~
     * function ($menu) {
     *    return [
     *        'label' => $menu['name'],
     *        'url' => [$menu['route']],
     *        'options' => $data,
     *        'items' => $menu['children']
     *        ]
     *    ]
     * }
     * ~~~
     * @param $userId
     * @param string $type
     * @param null $root
     * @param null $callback
     * @param bool $refresh
     * @return array|mixed
     */
    private static function getAssignedMenu($userId, $type = '1',$root = null, $callback = null, $refresh = false)
    {
        $cache = Configs::getCache();
        $authManager = Configs::getAuthManager();

        $menus = Menu::load()->where(['type'=>$type])->order('id asc')->column(Menu::getField());
        $key = __METHOD__.$userId.Configs::CACHE_TAG.(isset($_SESSION['_auth_token_'])?$_SESSION['_auth_token_']:'');

        if ($refresh || $cache === null || ($assigned = $cache->get($key)) === false) {
            $routes = $filter1 = $filter2 = [];
            if (!($userId === null || $userId == 0)) {
                foreach ($authManager->getPermissionsByUser($userId) as $name => $value) {
                    if ($name[0] === '/') {
                        if (substr($name, -2) === '/*') {
                            $name = substr($name, 0, -1);
                        }
                        $routes[] = $name;
                    }
                }
            }
            foreach ($authManager->getDefaultRoles() as $role) {
                foreach ($authManager->getPermissionsByRole($role) as $name => $value) {
                    if ($name[0] === '/') {
                        if (substr($name, -2) === '/*') {
                            $name = substr($name, 0, -1);
                        }
                        $routes[] = $name;
                    }
                }
            }
            foreach (\think\Config::get('access.default_action') as $permission) {
                if ($permission[0] === '/') {
                    if (substr($permission, -2) === '/*') {
                        $permission = substr($permission, 0, -1);
                    }
                    $routes[] = $permission;
                }
            }

            $routes = array_unique($routes);
            sort($routes);
            $prefix = '\\';
            foreach ($routes as $route) {
                if (strpos($route, $prefix) !== 0) {
                    if (substr($route, -1) === '/') {
                        $prefix = $route;
                        $filter1[] = $route . '%';
                    } else {
                        $filter2[] = $route;
                    }
                }
            }
            $assigned = [];

            if (count($filter2)) {
                $assigned = Menu::load()->where(['type'=>'1','route'=>['in',$filter2]])->order('id asc')->column('id');
            }
            if (count($filter1)) {
                foreach ($filter1 as $filter) {
                    $query = Menu::load()->where(['type'=>'1','route'=>['like',$filter]])->order('id asc')->column('id');
                    $assigned = array_merge($assigned, $query);
                }
            }

            //超级管理员，读取全部目录
            if ($userId == '100'){
                $assigned =  Menu::load()->where(['type'=>'1'])->order('id asc')->column('id');
            }

            $assigned = static::requiredParent($assigned, $menus);
            $assigned = static::normalizeMenu($assigned, $menus, $callback, $root);
            asort($assigned);
            if ($cache !== null) {
                $cache->set($key, $assigned, Configs::$cacheDuration);
            }
        }

        return $assigned;
    }

    /**
     * Ensure all item menu has parent.
     * @param  array $assigned
     * @param  array $menus
     * @return array
     */
    private static function requiredParent($assigned, &$menus)
    {
        if (!is_array($assigned)){
            return [];
        }
        $l = count($assigned);
        for ($i = 0; $i < $l; $i++) {
            $id = $assigned[$i];
            $parent_id = $menus[$id]['parent'];
            if ($parent_id !== null && !in_array($parent_id, $assigned)) {
                $assigned[$l++] = $parent_id;
            }
        }

        return $assigned;
    }

    /**
     * Parse route
     * @param  string $route
     * @return mixed
     */
    private static function parseRoute($route)
    {
        $res = '';
        if (!empty($route)) {
            $url = [];
            $r = explode('&', $route);
            $url[0] = $r[0];
            unset($r[0]);
            foreach ($r as $part) {
                $part = explode('=', $part);
                $url[$part[0]] = isset($part[1]) ? $part[1] : '';
            }
            $res = is_array($url) ? implode('',$url) : '';
        }

        return $res;
    }

    /**
     * Parse data
     * @param  string $data
     * @return mixed
     */
    private static function parseData($data)
    {
        if (!empty($data)) {
            $res = [];
            if ($d = @json_decode($data)){
                if (is_object($d)){
                    foreach ($d as $key => $value){
                        $res[$key] = $value;
                    }
                }
            }
            unset($d);
            return $res;
        }

        return null;
    }

    /**
     * Normalize menu
     * @param $assigned
     * @param $menus
     * @param $callback
     * @param null $parent
     * @return array
     */
    private static function normalizeMenu(&$assigned, &$menus, $callback, $parent = null)
    {
        $result = [];
        $order = [];
        foreach ($assigned as $id) {
            $menu = ArrayHelper::getValue($menus, $id);
            if ($menu['parent'] == $parent) {
                $menu['children'] = static::normalizeMenu($assigned, $menus, $callback, $id);
                if ($callback !== null) {
                    $item = call_user_func($callback, $menu);
                } else {
                    $item = [
                        'id' => $menu['id'],
                        'parent' => $menu['parent'],
                        'order' => $menu['order'],
                        'text' => $menu['name'],
                        'url' => static::parseRoute($menu['route']),
                        'data' => static::parseData($menu['data']),
                    ];
                    if ($menu['children'] != []) {
                        $item['children'] = $menu['children'];
                    }
                }
                $result[] = $item;
                $order[] = $menu['order'];
            }
        }
        if ($result != []) {
            array_multisort($order, $result);
        }

        return $result;
    }

    /**
     * generate the item of menu.
     * @param array $menuItems
     * @param array $options
     * @param bool $parent
     * @param string $prefixUrl
     * @return string
     */
    private static function generateMenuItem($menuItems, $options = ['class'=>'layui-nav layui-nav-tree'],$parent = true, $prefixUrl = '')
    {
        $sidebar = '';
        if($menuItems && is_array($menuItems)){
            //排序
            ksort($menuItems);
            if (empty($prefixUrl)){
                $prefixUrl = self::$_prefixUrl;
            }
            foreach ($menuItems as $menuItem){
                foreach ($menuItem['data'] as $key => $value){
                    if (empty($value)){
                        $menuItem['data'][$key] = '';
                    }
                }
                if (!empty($menuItem['url'])){
                    $prefixUrl .= $menuItem['url'] .'html';
                }else{
                    $prefixUrl = 'javascript:void(0);';
                }
                $liClass = isset($menuItem['data']['li_class'])
                    ? (!empty($menuItem['data']['li_class'])
                        ? $menuItem['data']['li_class']
                        : (!empty($menuItem['parent'])
                            ? 'layui-nav-child-item'
                            :'layui-nav-item'))
                    : (!empty($menuItem['parent'])
                        ? 'layui-nav-child-item'
                        :'layui-nav-item');
                $aClass = isset($menuItem['data']['a_class']) ? $menuItem['data']['a_class'] : '';
                $iClass = isset($menuItem['data']['i_class'] ) ? $menuItem['data']['i_class']  : '';
                $new = '';
                if (false){
                    $liClass .= ' '.$new;
                }
                if (!isset($menuItem['children'])){
                    $sidebarTag = 'li';
                    if (!empty($menuItem['parent'])){
                        $sidebarTag = 'dd';
                    }
                    $sidebar .= '<'.$sidebarTag.' class="'. $liClass .'" title="' . $menuItem['text'] . '">
                                <a class="'. $aClass .'" href="javascript:;" data-url="'.$prefixUrl.'">
                                <i class="' . $iClass . '"></i>
                                <cite class="title">' . $menuItem['text'] . '</cite>
                                </a>
                             </'.$sidebarTag.'>';
                }else{
                    $sidebar .= '<li class="'.$liClass.'">
                                 <a class="'.$aClass.'" href="javascript:;" data-url="'.$prefixUrl.'">
                                    <i class="' . $iClass . '"></i>
                                    <cite class="title">' . $menuItem['text'] . '</cite>
                                    <em class="layui-nav-more"></em>
                                </a>
                                <dl class="layui-nav-child">
                                    ' . self::generateMenuItem($menuItem['children'],[],false) . '
                                </dl>
                            </li>';
                }
            }
        }
        $attr = '';
        if (!empty($options)){
            foreach ($options as $key => $value){
                $attr .= ' ' . $key . '="' . $value . '"';
            }
        }
        if ($parent){
            $sidebar = '<ul '.$attr.'>'.$sidebar.'</ul>';
        }
        return $sidebar;
    }

    /**
     * generate the assigned  of menu for this user.
     * @param $userId
     * @param array $options
     * @param bool $refresh
     * @return string
     * @throws \think\Exception
     */
    public static function generateMenu($userId, $options = [], $refresh = true)
    {
        $menus = self::getAssignedMenu($userId, null ,null ,$refresh);
        if ($menus && $options){
            $menus = self::generateMenuItem($menus,$options);
        }elseif ($menus){
            $menus = self::generateMenuItem($menus);
        }else{
            Configs::getIdentity()->logout();
            throw new \think\Exception\HttpException(402,'该账号未激活-请联系管理员',null,['code'=>'402','msg'=>'该账号未激活-请联系管理员'],'402');
        }
        return $menus;
    }

    /**
     * get Menu list the assigned  of menu for this user.
     * @param $userId
     * @param array $options
     * @param string $app 'back'=>'1' or'front'=>'2'
     * @param bool $refresh
     * @param null $prefixUrl
     * @param null $suffix
     * @param null $query
     * @return array
     * @throws \think\Exception
     */
    public static function getMenu($userId = 0,$app='back', $options = ['class'=>'layui-nav layui-nav-tree'], $refresh = true,$prefixUrl =null,$suffix=null,$query=null)
    {
        if ($app == 'front'){
            $type = '2';
        }else{
            $type = '1';
        }
        $menus = self::getAssignedMenu($userId, $type,null ,null ,$refresh);
        if ($menus && $options){
            $prefixUrl = $prefixUrl ? $prefixUrl : self::$_prefixUrl;
            $suffix = $suffix ? $suffix : self::$_suffix;
            $query = $query ? $query : self::$_query;
            $menus = ['menus'=>$menus,'attr'=>$options,'prefix'=>$prefixUrl, 'suffix'=>$suffix.$query];
        }else{
//            Configs::getIdentity()->logout();
            throw new \think\Exception\HttpException(402,'该账号未激活-请联系管理员',null,['code'=>'402','msg'=>'该账号未激活-请联系管理员'],'402');
        }
        return $menus;
    }

}

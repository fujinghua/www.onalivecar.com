<?php

namespace app\common\components\rbac;

use \think\Config;
use think\Exception;
use \think\Request;
use \think\Log;
use app\manage\model\Identity;
use app\common\components\rbac\AuthManager;

class AccessControl
{

    /**
     * @return \app\common\components\rbac\AccessControl
     */
    private static $_instance;

    /**
     * @return \app\common\components\rbac\AccessControl
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new AccessControl();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->_authManager = AuthManager::getInstance();
    }


    /**
     * @var \app\manage\model\Identity
     */
    private $_user;

    /**
     * @var \app\common\components\rbac\AuthManager
     */
    private $_authManager;

    /**
     * @var array List of action that not need to check access.
     */
    public $allowActions = [
        '/home/*',
    ];

    /**
     * @return \app\common\components\rbac\AuthManager
     */
    public function getAuthManager()
    {
        if (!$this->_authManager) {
            $this->_authManager = AuthManager::getInstance();
        }
        return $this->_authManager;
    }

    /**
     * Get user
     * @return \app\manage\model\Identity
     */
    public function getUser($userid = 0)
    {
        if (!$this->_user instanceof Identity) {
            $this->_user = Identity::getIdentity();
            if (!$this->_user instanceof Identity) {
                $this->_user = Identity::getIdentityById($userid);
            }
        }
        return $this->_user;
    }

    /**
     * Get AuthManager
     * @return \app\common\components\rbac\AuthManager
     */
    public function getManager()
    {
        if (!$this->_authManager instanceof AuthManager) {
            $this->_authManager = AuthManager::getInstance();
        }
        return $this->_authManager;
    }

    /**
     * Set user
     * @param Identity|string $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * check user
     * @param int $userid
     * @param null $route
     * @param bool $log
     * @return bool
     */
    public function check($userid = 0, $route = null, $log = true)
    {
        $ret = true;
        if (substr($route, 0, 1) != '/') {
            $route = '/' . $route;
        }
        if (!($result = $this->isActive($route))) {
            $user = $this->getUser($userid);

            $result = $this->beforeAction($userid, $route);

            if (!$result) {
                $ret = false;
                $this->denyAccess($user);
            }
        }

        if ($log) {
            $this->afterAction($route, $result);
        }
        return $ret;
    }

    /**
     * @param string $userid
     * @param string $route
     * @return bool
     */
    protected function beforeAction($userid = '', $route = '')
    {
        $routeId = $route ? $route : $this->getActionUrl();
        $routeId = trim($routeId, '/');
        if ($this->can($userid, '/' . $routeId)) {
            return true;
        }
        return false;
    }

    /**
     * @param $route
     * @param $result
     */
    public function afterAction($route, $result)
    {
        $result = $result ? '允许' : '拒绝';
        $user_agent = '';
        if (Request::instance()->isAjax()){
            $user_agent .= '_AJAX';
        }
        if (Request::instance()->isPjax()){
            $user_agent .= '_PJAX';
        }
        if (Request::instance()->isMobile()){
            $user_agent .= '_PHONE';
        }

        $result = $result.'访问：'.$route.$user_agent;
        Log::record(Request::instance()->url() . $result);
        Identity::record($result);
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a client;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param  Identity $user
     * @throws Exception if the user is already logged in.
     */
    protected function denyAccess($user)
    {
        if ($user->isGuest()) {
//            $user->setLogout($user);
            throw new \think\Exception\HttpException(402, '没权限执行此操作', null, ['code' => '402', 'msg' => '没权限执行此操作'], '402');
        } else {
            throw new \think\Exception\HttpException(402, '没权限执行此操作', null, ['code' => '402', 'msg' => '没权限执行此操作'], '402');
        }
    }

    /**
     * @param $route
     * @return bool
     */
    protected function isActive($route)
    {
        $ret = false;
        $permissions = Config::get('access.default_action');
        $permissions = array_merge($this->allowActions, $permissions);
        $permissions = array_unique($permissions);
        foreach ($permissions as $permission) {
            if (stristr($permission, '*') !== false) {
                $tmpPermission = explode('*', $permission);
                $newPermission = trim($tmpPermission[0], '/');
                $tmp = explode('/', $newPermission);
                if (isset($tmp[1])) {
                    if ($permission == $route) {
                        $ret = true;
                        break;
                    } else {
                        if (strpos($route, '/' . $tmp[0] . '/' . $tmp[1] . '/') === 0) {
                            $ret = true;
                            break;
                        }
                    }
                } else {
                    if (strpos($route, '/' . $tmp[0] . '/') === 0) {
                        $ret = true;
                        break;
                    }
                }
            } else {
                if ($permission == $route) {
                    break;
                } else {
                    if (strpos($route, $permission.'/') === 0) {
                        $ret = true;
                        break;
                    }
                }
            }
        }
        return $ret;
    }


    /**
     * @description 当前请求路由
     * @return string
     */
    protected function getActionUrl()
    {
        // 获取当前访问路由
        return strtolower(Request::instance()->module() . '/' . Request::instance()->controller() . '/' . Request::instance()->action());
    }


    /**
     * @description 当前请求路由
     * @param $userid
     * @param $route
     * @return bool
     */
    protected function can($userid, $route)
    {
        $ret = false;
        $manager = $this->getManager();
        $result = $manager->getPermissionsByUser($userid);
        $permissions = array_keys($result);

        foreach ($permissions as $permission) {
            if (stristr($permission, '*') !== false) {
                $tmpPermission = explode('*', $permission);
                $newPermission = trim($tmpPermission[0], '/');
                $tmp = explode('/', $newPermission);
                if (isset($tmp[1])) {
                    if ($permission == $route) {
                        $ret = true;
                        break;
                    } else {
                        if (strpos($route, '/' . $tmp[0] . '/' . $tmp[1] . '/') === 0) {
                            $ret = true;
                            break;
                        }
                    }
                } else {
                    if (strpos($route, '/' . $tmp[0] . '/') === 0) {
                        $ret = true;
                        break;
                    }
                }
            } else {
                if ($permission == $route) {
                    $ret = true;
                    break;
                } else {
                    if (strpos($route, $permission.'/') === 0) {
                        $ret = true;
                        break;
                    }
                }
            }
        }

        return $ret;
    }
}
<?php

namespace app\common\components\rbac;

use \think\Model;

/**
 * Assignment represents an assignment of a role to a user.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class Assignment extends Model
{
    /**
     * @var string|integer user ID (see [[\yii\web\User::id]])
     */
    public $userId;
    /**
     * @var string the role name
     */
    public $roleName;
    /**
     * @var integer UNIX timestamp representing the assignment creation time
     */
    public $createdAt;

    /**
     * 架构函数
     * @access public
     * @param array|object $data 数据
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        foreach ($data as $key=>$value){
            if (property_exists($this,$key)){
                $this->$key = $value;
            }
        }
    }

}

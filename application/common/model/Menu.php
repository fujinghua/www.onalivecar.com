<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%menu}}". 菜单模型
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property string $route
 * @property integer $order
 * @property integer $type
 * @property string $data
 */
class Menu extends Model
{
    const TYPE_SYS = '1';   //type默认为1，表示网站后台系统菜单，
    const TYPE_APP = '2';    //type可能值为2，表示前端菜单，

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%menu}}';

    /**
     * @var string
     */
    protected $pk = 'id';

    // 数据表字段信息 留空则自动获取
    protected $field = [
        'id',
        'name',
        'parent',
        'route',
        'order',
        'type',
        'data',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'require', '名称 不能为空'],
            ['name', '0,128', '名称 长度为0-128位', 'length',],
            ['route', '0,256', '路由地址 长度为0-256位', 'length',],
            ['parent', 'number', '父级需要是 数值',],
            ['order', 'number', '排序是 数值',],
            ['app', self::TYPE_SYS . ',' . self::TYPE_APP, '类型 只能是' . self::TYPE_SYS . '或' . self::TYPE_APP, 'between',],
            ['type', self::TYPE_SYS . ',' . self::TYPE_APP, '类型 只能是' . self::TYPE_SYS . '或' . self::TYPE_APP, 'between',],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'parent' => '上级',
            'route' => '路由',
            'order' => '排序',
            'app' => '菜单类型',
            'data' => '补充',
        ];
    }

    /**
     *
     */
    public static function getField()
    {
        $model = new Menu();
        return $model->field;
    }

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate()
    {
        return MenuValidate::load();
    }

    /**
     * @param $data
     * @param string $scene
     * @return bool
     */
    public static function check($data, $scene = '')
    {
        $validate = self::getValidate();

        //设定场景
        if (is_string($scene) && $scene !== '') {
            $validate->scene($scene);
        }

        return $validate->check($data);
    }

}

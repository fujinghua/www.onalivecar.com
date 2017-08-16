<?php

namespace app\common\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%images}}".
 *
 */
class Images extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%images}}';

    protected $field = [
        'id',
        'target_id',
        'type',
        'url',
        'url_icon',
        'url_title',
        'created_at',
    ];

    // 保存自动完成列表
    protected $auto = [];
    // 新增自动完成列表
    protected $insert = [];
    // 更新自动完成列表
    protected $update = [];

    public static function getTypeList(){
        return self::T('type');
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'rule'=>[
                ['target_id','number','目标索引 无效'],
            ],
            'msg'=>[]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'target_id' => '名称',
            'type' => '类型',
            'url' => '地址',
            'url_icon' => '缩略图地址',
            'url_title' => '图片ALT',
            'created_at' => '变更时间',
        ];
    }
}

<?php

namespace app\common\lang;

class CarAttr{
    /**
     * 单例容器
     * @var
     */
    private static $_instance;

    /**
     * 单例接口
     * @return CarAttr
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    /**
     * 单例
     * LangHelper constructor.
     */
    private function __construct()
    {

    }


    /**
     * 模型语言包
     * @var array
     */
    private static $attr = [
        '车身篇'=>[
            '车身尺寸-长*宽*高'=>[
                '3.7M以下为微型车',
                '3.7-4.3M为小型车',
                '4.3-4.6M为紧凑型车',
                '4.6-4.9M为中型车',
                '5.1M 以上为豪华车',
            ],
            '以轴距为判别依据'=>[
                '2350mm 以下为微型车',
                '2350mm-2500mm小型车',
                '2500mm-2700mm紧凑型车',
                '2700mm-2800mm中型车',
                '2800mm-2900mm中大型车',
                '2900mm 以上大型豪华车',
            ],
            '前/后轮距',
            '最小离地间隙',
            '最小转弯直径',
            '车体结构'=>[
                '承载式',
                '半承载式',
                '非承载式',
                '空间构架式',
            ],
            '接近角/离去角',
            '风阻系数',
            '最大涉水深度',
            '行李舱容积',
            '',
        ],
        '动力/传动篇'=>[
            '气缸排列形式'=>[
                '直列发动机',
                'V型发动机',
                'W型发动机',
                '水平对置发动机',
                '转子发动机',
            ],
            '缸盖材料/缸体材料'=>[],
            '气缸数',
            '每缸气门数',
            '工作方式'=>[
                '自然吸气',
                '涡轮增压',
                '机械增压',
                '双增压',
            ],
            '汽缸容积/排气量',
            '压缩比',
            '最大涉水深度',
            '行李舱容积',
            '',
        ],
    ];
}
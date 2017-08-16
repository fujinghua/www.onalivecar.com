<?php

namespace app\common\components\decode\model;

use app\common\model\Model;
use app\common\components\decode\DecodeHelper;
use app\common\components\decode\validate\GpsValidate;
use app\common\components\decode\replace\Gps as Replace;

class Gps extends Model {

    /**
     * @var string
     */
    protected $pk = 'id';

    // 数据表字段信息 留空则自动获取
    protected $field = [
        'id',
        'SIM',
        'alarm_flag',
        'status',
        'lng',
        'lat',
        'high',
        'speed',
        'orientation',
        'create_time',
        'remark',
        'is_delete',
        'update_time',
    ];

    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'gps';
    }

    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = [];

    /**
     * 自动完成规则
     * @author Sir Fu
     */
    protected $_auto = [];

    /**
     * @param array $pack
     * @param array $source
     * @return false|int
     */
    public function newPack($pack = [], $source = [])
    {
        $ret = false;
        $options = array_keys(Replace::$description);
        $validate = new GpsValidate();
        $data = $this->getInitData($pack,$source,$options);
        $result = $validate->check($data);

        if ($result){
            $this->save($data); //存入数据库
        }

        return $ret;
    }

    /**
     * @param array $pack
     * @param array $source
     * @param array $options
     * @return array
     */
    public function getInitData($pack = [], $source = [], $options = [])
    {
        $ret = [];

        if (!empty($pack) && is_array($pack)){
            $ret['SIM'] = isset($source['SIM']) ? DecodeHelper::getTrueSim($source['SIM']) : '';
            foreach ($pack as $key => $value){
                switch ($key){
                    case 'warning':{
                        $ret['alarm_flag'] = $value;
                    }break;
                    case 'direction':{
                        $ret['orientation'] = $value;
                    }break;
                    case 'height':{
                        $ret['high'] = $value;
                    }break;
                    case 'time':{
                        $value = explode('-',$value);
                        $value = implode('',$value);
                        $value = substr(date('Y'),0,2).$value;
                        $ret['update_time'] = date('Y-m-d H:i:s',strtotime($value));
                    }break;
                    default:{
                        $ret[$key] = $value;
                    }break;
                }
            }
            $ret['create_time'] = date('Y-m-d H:i:s');

            $end = \app\manage\model\Gps::get()->where('SIM',$ret['SIM'])->order('update_time','DESC')->limit(1)->select();
            foreach($end as $key=>$value){
                $update_time = $value->getData('update_time');
                if ($update_time >= $ret['update_time']){
                    $ret = [];
                }
            }

        }

        return $ret;
    }

}
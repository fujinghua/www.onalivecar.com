<?php

namespace app\common\model;

use app\common\model\Model;
use app\common\model\BuildingBase;
use app\common\model\Region;
use app\common\model\HandHouse;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property double $region_id
 * @property integer $parent
 * @property string $name
 * @property integer $level
 * @property integer $order
 * @property string $code
 * @property string $name_en
 * @property string $short_name_en
 * @property string $data
 *
 * @property BuildingBase[] $buildingBases
 * @property Region $region
 * @property HandHouse[] $secondHandHouses
 */
class City extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%city}}';

    protected $field = [
        'id',
        'is_delete',
        'region_id',
        'parent',
        'name',
        'level',
        'order',
        'code',
        'name_en',
        'short_name_en',
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
            'rule'=>[
                ['is_delete','in:0,1','时效 无效'],
                ['parent','number','父级 无效'],
                ['level','number','等级 无效'],
                ['order','number','顺序 无效'],
                ['region_id','number','地区 无效'],
                ['name','max:100',],
                ['code','max:100',],
                ['name_en','max:100',],
                ['short_name_en','max:10',],
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
            'id' => 'ID',
            'is_delete' => '时效;0=无效;1=有效;',
            'region_id' => '地区父级',
            'parent' => 'Parent',
            'name' => 'Name',
            'level' => '等级;0=全国,1=省级,2=市级,3=县级',
            'order' => 'Order',
            'code' => 'Code',
            'name_en' => 'Name En',
            'short_name_en' => 'Short Name En',
            'data' => 'Data',
        ];
    }

    /**
     * @param array|string $where
     * @return array
     */
    public static function getCityList($where = null)
    {
        $ret = [];
        $query = City::load();
        if (empty($where)){
            $where = ['level'=>'2'];
        }
        $query = $query->where($where);
        $result = $query->select();
        if ($result){
            $helper = City::getHelper();
            $result = $helper::toArray($result);
            foreach ($result as $key => $value){
                $ret[$value['id']] = $value['name'];
            }
        }
        return $ret;
    }

    /**
     * @return array
     */
    public static function getLevelList()
    {
        return self::T('level');
    }


    /**
     * @param $id
     * @return array
     */
    public static function getRegionAllChild($id)
    {
        $ret = [];
        $result = Region::load()->where(['parent'=>$id])->select();
        if ($result){
            $helper = City::getHelper();
            $result = $helper::toArray($result);
            foreach ($result as $key => $value){
                $ret[$value['id']] = $value['name'];
            }
        }
        return $ret;
    }

    /**
     * @param $id
     * @return string|Region
     */
    public static function getRegionById($id)
    {
        $ret = '1';
        $result = Region::load()->where(['id'=>$id])->find();
        if ($result){
            $ret = $result;
        }
        return $ret;
    }

    /**
     * @param $name
     * @param $returnId
     * @param null $parent
     * @return string | Region
     */
    public static function getRegionByName($name,$parent = null,$returnId = false)
    {
        $ret = '1';
        $result = Region::load()->where(['name'=>$name])->select();
        if ($result){
            if (!$parent || count($result) <= 1 ){
                $ret = isset($result[0]) ? $result[0] : '1';
            }else{
                $result = Region::load()->alias('t')
                    ->join([Region::tableName()=>'r'],'t.parent = r.id','LEFT')
                    ->join([Region::tableName()=>'rr'],'r.parent = rr.id','LEFT')
                    ->where(['t.name'=>$name])
                    ->where(" r.name = '".$parent."' or  rr.name = '".$parent."' ")
                    ->field('*,t.id as target_id')
                    ->find();
                if ($result){
                    $ret = Region::load()->where(['id'=>$result->target_id])->find();
                }
            }
            if ($ret instanceof Region && $returnId){
                $ret = $ret->id;
            }
        }
        return $ret;
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getParent(){
        return $this->hasOne(ucfirst(City::tableNameSuffix()), 'id','parent');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getBuildingBases()
    {
        return $this->hasMany(ucfirst(BuildingBase::tableNameSuffix()), 'id','city_id');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function getRegion()
    {
        return $this->hasOne(ucfirst(Region::tableNameSuffix()), 'region_id','id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getSecondHandHouses()
    {
        return $this->hasMany(ucfirst(HandHouse::tableNameSuffix()), 'id','city_id');
    }
}

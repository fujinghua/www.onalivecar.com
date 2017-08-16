<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: Sir Fu
// +----------------------------------------------------------------------
// | 版权申明：零云不是一个自由软件，是零云官方推出的商业源码，严禁在未经许可的情况下
// | 拷贝、复制、传播、使用零云的任意代码，如有违反，请立即删除，否则您将面临承担相应
// | 法律责任的风险。如果需要取得官方授权，请联系官方http://www.lingyun.net
// +----------------------------------------------------------------------
namespace app\back\controller;


use app\common\controller\BackController;

class DataController extends BackController
{

    /**
     * @description 统计
     * @param integer $pageNumber
     * @return string
     */
    public function indexAction($pageNumber = 1)
    {
        return '';
            //发单数量、出车数量、行驶公里数、行驶时间、抢单数量
        //出车情况（时间段、地区分组）
        $dataProvider = [];
//        $day = [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')];
//        $tomorrow = [date("Y-m-d 00:00:00",strtotime("-1 day")), date("Y-m-d 23:59:59",strtotime("-1 day"))];
//        $mouth = [date('Y-m-01', strtotime(date("Y-m-d"))), date('Y-m-d')];
        $day = ['2017-03-23 00:00:00', '2017-03-23 23:59:59'];
        $tomorrow = ['2017-03-22 00:00:00', '2017-03-22 23:59:59'];
        $mouth = ['2017-03-01 00:00:00', '2017-03-23 23:59:59'];
        for ($i = 0; $i<3;$i++){
            $tmp = [];
            if ($i == 0){
                $between = $day;
                $key = 'day';
                $name = '今日';
            }elseif($i == 1 ){
                $between = $tomorrow;
                $key = 'preDay';
                $name = '昨日';
            }else{
                $between=$mouth;
                $key = 'mouth';
                $name = '本月';
            }
            $tmp['name'] = $name;
            $tmp['takeCarOrderCount'] = TakeCarOrder::load()->whereTime('create_time', 'between', $between)->count();
            $tmp['outCarCount'] = OutCar::load()->whereTime('create_time', 'between', $between)->count();
            $tmp['outCarMileage'] =  number_format(TakeCarOrder::load()->whereTime('create_time', 'between', $between)->sum('driver_mileage'), 2, '.', ',');
            $tmp['outCarTime'] = TakeCarOrder::load()->whereTime('create_time', 'between', $between)->sum('driver_time');
            $tmp['grabSingleCount'] = TakeCarOrder::load()->where('is_delete','1')->whereTime('create_time', 'between', $between)->count();
            $dataProvider[$key] = $tmp;
        }
        $this->assign('meta_title', "数据统计");
        $this->assign('dataProvider', $dataProvider);
        return view('datacount/count');
    }

    /***
     * @param string $start
     * @param string $end
     * @return \think\response\Json
     */
    public function chartAction($start = '',$end = ''){
        return '';
        $res = [];

        $newStart = date('Y-m-d 00:00:00', time() - date('t', time()) * 24 * 60 * 60);
        if (strtotime($start)) {
            $newStart = date('Y-m-d H:i:s', strtotime($start));
        }
        $newEnd = date('Y-m-d H:i:s', time());
        if (strtotime($end)) {
            $newEnd = date('Y-m-d H:i:s', strtotime($end));
        }
        if ($newStart > $newEnd) {
            $tmp = $newStart;
            $newStart = $newEnd;
            $newEnd = $tmp;
        }

        $yAxis = [];
        $xAxis = ["成都","上海","海口","三亚","文昌","琼海","万宁","五指山","东方","儋州","临高","澄迈","定安","屯昌","昌江","白沙","琼中","陵水","保亭","乐东","博鳌"];
        $minYAxis = 0;
        $maxYAxis = 0;
        $newData = [];
        $startTime = $newStart;
        $endTime = $newEnd;
        $between = [$startTime, $endTime];
        for ($i=0;$i<count($xAxis);$i++){
            $value = OutCar::load()->alias('t')
                ->join('city c','t.start_city_id = c.id')
                ->where('c.name',$xAxis[$i])
                ->whereTime('out_car_time', 'between', $between)
                ->count();
            $newData[] = ['name'=>$xAxis[$i],'value'=>$value];
            if ($value>$maxYAxis){
                $maxYAxis = $value;
            }
        }

        $step = ceil(ceil(($maxYAxis - $minYAxis) / 5) / 10) * 10;
//        $yAxis[] = $minYAxis + $step * 1;
//        $yAxis[] = $minYAxis + $step * 2;
//        $yAxis[] = $minYAxis + $step * 3;
//        $yAxis[] = $minYAxis + $step * 4;
//        $yAxis[] = $minYAxis + $step * 5;
        $res['title'] = '出车情况';
        $res['legend'] = ['上月','本月'];
        $res['dataCount'] = $newData;
        $res['yAxis'] = $yAxis;
        $res['xAxis'] = $xAxis;
        $res['min'] = $minYAxis - $step;
        $res['max'] = $minYAxis + $step * 6;
        $res['start'] = $newStart;
        $res['end'] = $newEnd;
        return json(['code'=>'1','options'=>$res]);
    }
}

<?php

namespace app\api\controller;

use app\common\controller\ApiController;

use app\common\model\Brand;
use app\common\model\Slider;

/**
 * 默认控制器
 * @author Sir Fu
 */
class HomeController extends ApiController
{
    /**
     * 默认方法
     * @author Sir Fu
     */
    //首页信息
    public function indexAction()
    {
        $ag = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
        foreach ($ag as $k => $v) {
            $res[$k]['lotter'] = $v;
            $res[$k]['brands'] = [];
        }
        $brand = Brand::load();
        $more = $brand->where(['is_delete' => '1'])->column('id,letter,name,icon');
        $arr = $brand->asArray($more);
        foreach ($arr as $key => $value) {
            if ($value['letter'] == 'a') {
                unset($value['letter']);
                $res[0]['brands'][] = $value;
            } elseif ($value['letter'] == 'b') {
                unset($value['letter']);
                $res[1]['brands'][] = $value;
            } elseif ($value['letter'] == 'c') {
                unset($value['letter']);
                $res[2]['brands'][] = $value;
            } elseif ($value['letter'] == 'd') {
                unset($value['letter']);
                $res[3]['brands'][] = $value;
            } elseif ($value['letter'] == 'e') {
                unset($value['letter']);
                $res[4]['brands'][] = $value;
            } elseif ($value['letter'] == 'f') {
                unset($value['letter']);
                $res[5]['brands'][] = $value;
            } elseif ($value['letter'] == 'g') {
                unset($value['letter']);
                $res[6]['brands'][] = $value;
            } elseif ($value['letter'] == 'h') {
                unset($value['letter']);
                $res[7]['brands'][] = $value;
            } elseif ($value['letter'] == 'i') {
                unset($value['letter']);
                $res[8]['brands'][] = $value;
            } elseif ($value['letter'] == 'j') {
                unset($value['letter']);
                $res[9]['brands'][] = $value;
            } elseif ($value['letter'] == 'k') {
                unset($value['letter']);
                $res[10]['brands'][] = $value;
            } elseif ($value['letter'] == 'l') {
                unset($value['letter']);
                $res[11]['brands'][] = $value;
            } elseif ($value['letter'] == 'm') {
                unset($value['letter']);
                $res[12]['brands'][] = $value;
            } elseif ($value['letter'] == 'n') {
                unset($value['letter']);
                $res[13]['brands'][] = $value;
            } elseif ($value['letter'] == 'o') {
                unset($value['letter']);
                $res[14]['brands'][] = $value;
            } elseif ($value['letter'] == 'p') {
                unset($value['letter']);
                $res[15]['brands'][] = $value;
            } elseif ($value['letter'] == 'q') {
                unset($value['letter']);
                $res[16]['brands'][] = $value;
            } elseif ($value['letter'] == 'r') {
                unset($value['letter']);
                $res[17]['brands'][] = $value;
            } elseif ($value['letter'] == 's') {
                unset($value['letter']);
                $res[18]['brands'][] = $value;
            } elseif ($value['letter'] == 't') {
                unset($value['letter']);
                $res[19]['brands'][] = $value;
            } elseif ($value['letter'] == 'u') {
                unset($value['letter']);
                $res[20]['brands'][] = $value;
            } elseif ($value['letter'] == 'v') {
                unset($value['letter']);
                $res[21]['brands'][] = $value;
            } elseif ($value['letter'] == 'w') {
                unset($value['letter']);
                $res[22]['brands'][] = $value;
            } elseif ($value['letter'] == 's') {
                unset($value['letter']);
                $res[23]['brands'][] = $value;
            } elseif ($value['letter'] == 'y') {
                unset($value['letter']);
                $res[24]['brands'][] = $value;
            } elseif ($value['letter'] == 'z') {
                unset($value['letter']);
                $res[25]['brands'][] = $value;
            }
        }
        $info = [
            'hot' => [
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '1', 'name' => '奥迪'],
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '2', 'name' => '奔驰'],
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '3', 'name' => '林肯']
            ],
            'more' => $res,
            'main' => [
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '1', 'name' => '奥迪'],
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '2', 'name' => '奔驰'],
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '3', 'name' => '林肯']
            ],
        ];
        $ret = ['code' => '0000', 'code_str' => '获取数据成功', 'info' => $info];
        return json($ret);
    }

    /**
     * 首页轮播
     * @author Sir Fu
     */
    public function sliderAction()
    {
        $info = [];
        $ret = ['code' => '0000', 'code_str' => '获取数据成功', 'info' => $info];
        $data = [];
        $model = Slider::load();
        $result = $model->where(['is_delete' => '1', 'status' => '2'])->limit('6')->column('url,url_icon,target,title');
        if (!empty($result)) {
            foreach ($result as $value) {
                $data[] = $value;
            }
        }
        $ret['info'] = $data;
        return json($ret);
    }


}

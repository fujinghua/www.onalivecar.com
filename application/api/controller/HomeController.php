<?php

namespace app\api\controller;

use app\common\controller\ApiController;

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
        $info = [
            'hot' => [
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'id' => '1', 'name' => '奥迪Q7'],
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'id' => '2', 'name' => '奔驰'],
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'id' => '3', 'name' => '林肯'],
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'id' => '4', 'name' => '玛莎拉蒂']
            ],
            'main' => [
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '1', 'name' => '奥迪'],
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '2', 'name' => '奔驰'],
                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '3', 'name' => '林肯']
            ],
            'banner' => []
        ];
        $ret = ['code' => '0000', 'code_str' => '获取数据成功', 'info' => $info];
        $data = [];
        $where = ['is_delete' => '1', 'status' => '2'];
        $field = ['url', 'url_icon', 'target', 'title', 'description',];
        $limit = 6;
        $model = Slider::load();
        $result = $model->field($field)->where($where)->limit($limit)->order('order ASC')->select();
        $result = $model->asArray($result); //此方法是我自己写的
        if (!empty($result)) {
            $data = $result;
        }
        $ret['info']['banner'] = $data;
        return json($ret);
    }

//    /**
//     * 首页轮播
//     * @author Sir Fu
//     */
//    public function sliderAction()
//    {
//        $info = [
//            'hot' => [
//                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'id' => '1', 'name' => '奥迪Q7'],
//                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'id' => '2', 'name' => '奔驰'],
//                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'id' => '3', 'name' => '林肯'],
//                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'id' => '4', 'name' => '玛莎拉蒂']
//            ],
//            'main' => [
//                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '1', 'name' => '奥迪'],
//                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '2', 'name' => '奔驰'],
//                ['icon' => 'http://img1.xcarimg.com//PicLib//logo//pl1_160s.png?t=20170905', 'cate_id' => '3', 'name' => '林肯']
//            ],
//            'banner' => []
//        ];
//        $ret = ['code' => '0000', 'code_str' => '获取数据成功', 'info' => $info];
//        $data = [];
//        $where = ['is_delete' => '1', 'status' => '2'];
//        $field = ['url', 'url_icon', 'target', 'title', 'description',];
//        $limit = 6;
//        $model = Slider::load();
//        $result = $model->where($where)->limit($limit)->order('order ASC')->getField(implode(',', $field));
//        $result = $model->asArray($result); //此方法是我自己写的
//        if (!empty($result)) {
//            $data = $result;
//        }
//        $ret['info']['banner'] = $data;
//        return json($ret);
//    }


}

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
    //测试下
    public function indexAction()
    {
        $userId = $this->context->userId;
        $data = [
            'banner' => []
        ];
        return json(['status' => '1', 'info' => 'API']);
    }

    /**
     * 首页轮播
     * @author Sir Fu
     */
    public function sliderAction()
    {
        $ret = ['status' => '1', 'info' => '成功','data'=>[]];
        $data = [];
        $where = ['is_delete'=>'1','status'=>'2'];
        $field = ['url', 'url_icon', 'target', 'title', 'description',];
        $limit = 6;
        $model = Slider::load();
        $result = $model->where($where)->limit($limit)->order('order ASC')->getField(implode(',',$field));
        $result = $model->asArray($result); //此方法是我自己写的
        if (!empty($result)){
            $data = $result;
        }
        $ret['data'] = $data;
        return json($ret);
    }


}

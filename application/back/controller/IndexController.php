<?php

namespace app\back\controller;

use app\common\controller\BackController;
use app\common\model\Model;


/**
 * 后台默认控制器
 * @author Sir Fu
 */
class IndexController extends BackController
{

    /**
     * 默认方法
     * @author Sir Fu
     */
    public function indexAction()
    {
        $this->assign('meta_title', "后台首页");
        return view('index');
    }

    /**
     * @description 控制面板
     * @author Sir Fu
     */
    public function homeAction()
    {
        $this->assign('meta_title', "控制面板");
        return view('home');
    }

    /**
     * @description The APP 全局MISS路由，一个父级操作.
     */
    public function missAction()
    {
        if ($this->getRequest()->isAjax()){
            $this->HttpException('402');
        }else{
            $this->HttpException();
        }
    }


    /**
     * @description 常见问题
     * @param $page
     * @return \think\Request
     * @author Sir Fu
     */
    public function faqAction($page = 0)
    {
        $this->assign('meta_title', "常见问题");
        if ($this->getRequest()->isAjax()){
            $ret = ['data'=>[
                [
                    'group'=>'分组一',
                    'question'=>[
                        [
                            'title'=>'标题一',
                            'item'=>[
                                'some about...',
                                'some about...',
                                'some about...'
                            ]
                        ],
                        [
                            'title'=>'标题二',
                            'item'=>[
                                'some about...',
                                'some about...',
                                'some about...'
                            ]
                        ]
                    ]
                ],
                [
                    'group'=>'分组二',
                    'question'=>[
                        [
                            'title'=>'标题一',
                            'item'=>[
                                'some about...',
                                'some about...',
                                'some about...'
                            ]
            ],
                        [
                            'title'=>'标题二',
                            'item'=>[
                                'some about...',
                                'some about...',
                                'some about...'
                            ]
                        ]
                    ]
                ],
            ]];
            return json($ret);
        }
        return view('faq');
    }

    /**
     * @description 菜单列表
     * @author Sir Fu
     */
    public function navAction()
    {
        return $this->nav($this->getIdentity('id'));
    }
}

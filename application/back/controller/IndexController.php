<?php

namespace app\back\controller;

use app\common\controller\BackController;

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

    /**
     * @description 代码更新，只能是超级管理员更新
     * @author Sir Fu
     */
    public function deployAction()
    {
        $ret = ['status'=>'0','更新失败'];
        if ($this->getIdentity('id') == config('identity.superId')){
            $commands = [];
            $commands[] = 'cd /www/wwwroot/www.onalivecar.com'; //打开目录
            $commands[] = 'git checkout master'; // 切换到主分支
            if (isset($_GET['merge'])){
                $commands[] = 'git add -A'; //  提交所有变化
                $commands[] = 'git commit -m \'在'.date('Y-m-d H:i:s').'手动更新代码\'';
                $commands[] = 'git fetch origin master'; // 更新代码到本地仓库
                $commands[] = 'git merge origin/master'; // 将远端master分支的代码merge进当前checkout分支
                $commands[] = 'git push origin master:master'; // 使用merge工具解决merge冲突(git push A B:C,其中A和C是分别remote端的一个repository的名字和branch的名字，B是本地端branch的名字)
            }else{
                $commands[] = 'git pull origin master'; //直接从GitHub上更新代码到本地，会覆盖本地已修改的
            }
            if (function_exists('shell_exec')){
                $ret = ['status'=>'1','更新成功'];
                foreach ($commands as $command){
                    shell_exec($command);
                }
            }
        }
        return json($ret);
    }
}

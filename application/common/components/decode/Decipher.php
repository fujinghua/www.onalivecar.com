<?php

namespace app\common\components\decode;

use app\common\components\decode\IDecipher;

/**
 * Class Decipher
 * @package app\common\components\decode
 */
abstract class Decipher implements IDecipher{

    protected $model;
    protected $codeBody;
    protected $sum = 0;
    protected $result = [];
    protected $pack = [];
    protected $packTrans = [];
    protected $startLength = 0;

    /**
     * @description 获取数据
     * @param string $str
     * @param int $sum
     * @return array
     */
    public function getData($str,$sum = 0)
    {
        if ($str && is_string($str)){
            $this->codeBody = $str;
        }
        if (is_int($sum) && $sum > 0){
            $this->sum = $sum;
        }
        $this->beforeAction();
        $this->getPack();
        $this->getPackTrans();
        $this->setRes();
        $this->afterAction();
        return $this->result;
    }

    /**
     * @description 设置最后解析数据
     */
    protected function setRes(){

        $this->result['pack'] = $this->pack;
        $this->result['packTrans'] = $this->packTrans;
    }

    /**
     * @description 解析前操作
     */
    protected abstract function beforeAction();

    /**
     * @description 读取解析
     */
    protected abstract function getPack();

    /**
     * @description 解析信息包
     */
    protected abstract function getPackTrans();

    /**
     * @description 解析后操作
     */
    protected abstract function afterAction();

}

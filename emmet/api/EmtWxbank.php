<?php
namespace emmet\api;
// +----------------------------------------------------------------------
// | 作  者: LiuChuang
// +----------------------------------------------------------------------
// | 日  期: 2021/8/28 0028 16:25
// +----------------------------------------------------------------------
// | 备  注: 银行接口
// +----------------------------------------------------------------------

use emmet\api\Lib\Common;
use emmet\api\Lib\Tools;

class EmtWxbank extends Common {

    const BANK_NAME_URL         = '/v1/Mchbank/getAccountBank';//获取银行名称接口路径
    const BANK_ADDRESSCODE_URL  = '/v1/Mchbank/getBankAddressCode';//获取开户银行省市编码路径
    const BANK_NAME_CODE_URL    = '/v1/Mchbank/getBankNameCode';


    //获取开户银行的银行名称,输入银行名称没有找到银行名字，那就是其他银行
    public function getAccountBank($bank_name){
        if (!$this->getAuthorization()){
            return false;
        }
        $result = Tools::httpGet(self::DOMAIN_NAME.self::BANK_NAME_URL.'?bank_name='.$bank_name,$this->authorization);
        if ($result){
            $json = json_decode($result,true);
            return $json;
        }
        return false;
    }

    //获取开户银行省市编码
    public function getBankAddressCode($address){
        if (!$this->getAuthorization()){
            return false;
        }
        $result = Tools::httpGet(self::DOMAIN_NAME.self::BANK_ADDRESSCODE_URL.'?address='.$address,$this->authorization);
        if ($result){
            $json = json_decode($result,true);
            return $json;
        }
        return false;
    }


    //获取开户银行联行号和开户银行全称
    public function getBankNameCode($bank_name){
        if (!$this->getAuthorization()){
            return false;
        }
        $result = Tools::httpGet(self::DOMAIN_NAME.self::BANK_NAME_CODE_URL.'?bank_name='.$bank_name,$this->authorization);
        if ($result){
            $json = json_decode($result,true);
            return $json;
        }
        return false;
    }

}
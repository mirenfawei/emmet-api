<?php
namespace emmet\api;
// +----------------------------------------------------------------------
// | 作  者: LiuChuang
// +----------------------------------------------------------------------
// | 日  期: 2021/8/28 0028 11:11
// +----------------------------------------------------------------------
// | 备  注: 进件提交
// +----------------------------------------------------------------------

use emmet\api\Lib\Common;
use emmet\api\Lib\Tools;

class EmtWxapply extends Common {


    const BUSSINESS_DATA_URL = '/v1/mchsubmit/applyment';//进件提交路径


    public function subimt($data){
        if (!$this->getAuthorization()){
            return false;
        }
        $result       =  Tools::httpPost(self::DOMAIN_NAME.self::BUSSINESS_DATA_URL,$data,$this->authorization);
        if ($result){
            $json = json_decode($result,true);
            return $json;
        }
        return false;
    }



}
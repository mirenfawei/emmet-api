<?php
namespace emmet\api;
// +----------------------------------------------------------------------
// | 作  者: LiuChuang
// +----------------------------------------------------------------------
// | 日  期: 2021/8/28 0028 15:23
// +----------------------------------------------------------------------
// | 备  注: 媒体类上传
// +----------------------------------------------------------------------

use emmet\api\Lib\Common;
use emmet\api\Lib\Tools;

class EmtWxmedia extends Common {


    const MEDIA_UPLOAD_IMAGES   = '/v1/mchmedia/uploadImageGetMediaId';
    const MEDIA_UPLOAD_VEDIO    = '/v1/mchmedia/uploadVideoGetMediaId';

    //图片上传获取media_id,$base64_image图片的base64文本内容
    public function uploadImageGetMediaId($base64_image){
        if (!$this->getAuthorization()){
            return false;
        }
        $data = [
            'base64' => $base64_image
        ];
        $result = Tools::httpPost(self::DOMAIN_NAME.self::MEDIA_UPLOAD_IMAGES,$data,$this->authorization);
        if ($result){
            $json = json_decode($result,true);
            return $json;
        }
        return false;
    }

    //视频上传获取media_id
    public function uploadVideoGetMediaId($base64_video){
        if (!$this->getAuthorization()){
            return false;
        }
        $data = [
            'base64' => $base64_video
        ];
        $result = Tools::httpPost(self::DOMAIN_NAME.self::MEDIA_UPLOAD_VEDIO,$data,$this->authorization);
        if ($result){
            $json = json_decode($result,true);
            return $json;
        }
        return false;
    }

}
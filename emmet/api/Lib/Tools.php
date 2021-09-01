<?php
namespace emmet\api\Lib;
// +----------------------------------------------------------------------
// | 作  者: LiuChuang
// +----------------------------------------------------------------------
// | 日  期: 2021/8/27 0027 17:16
// +----------------------------------------------------------------------
// | 备  注: 
// +----------------------------------------------------------------------

class Tools{

    /**
     * 产生随机字符串
     * @param int $length 指定字符长度
     * @param string $str 字符串前缀
     * @return string
     */
    public static function createNoncestr($length = 32, $str = ""){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 生成验证签名
     * @param array $option
     * @param string $app_secret
     * @return string
     */
    public static function getSign($option, $app_secret){
        ksort($option);
        $option['key'] = $app_secret;
        return strtolower(md5(urldecode(http_build_query($option))));
    }

    /**
     * 以post方式提交请求
     * @param string $url
     * @param array|string $data
     * @return
     */
    static public function httpPost($url, $data,$authorization = []){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $authorization);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, self::_buildPost($data));
        list($content, $status) = array(curl_exec($curl), curl_getinfo($curl), curl_close($curl));
        return $content;
    }

    /**
     * 以get方式提交请求
     * @param $url
     * @return bool|mixed
     */
    static public function httpGet($url,$authorization = []){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$authorization);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        list($content, $status) = array(curl_exec($curl), curl_getinfo($curl), curl_close($curl));
        return $content;
    }

    /**
     * POST数据过滤处理
     * @param array $data
     * @return array
     */
    static private function _buildPost(&$data){
        if (is_array($data)) {
            foreach ($data as &$value) {
                if ($value==''){
                    return $data;
                }
                if (is_string($value) && $value[0] === '@' && class_exists('CURLFile', false)) {
                    $filename = realpath(trim($value, '@'));
                    file_exists($filename) && $value = new CURLFile($filename);
                }
            }
        }
        return $data;
    }

}
<?php
namespace emmet\api\Lib;
// +----------------------------------------------------------------------
// | 作  者: LiuChuang
// +----------------------------------------------------------------------
// | 日  期: 2021/8/27 0027 17:03
// +----------------------------------------------------------------------
// | 备  注: 基础类
// +----------------------------------------------------------------------

use emmet\api\Exception\InvalidArgumentException;

class Common{

    const DOMAIN_NAME       = 'http://api.wxservice.51jh.la';//根域名
    const OAUTH_TOKEN_URL   = '/v1/token/';//获取access_token的路径
    const OAUTH_REFRESH_URL = '/v1/refresh/';//通过refresh_token获取access_token

    protected $app_id;
    protected $app_secret;
    protected $mobile;
    protected $access_token;
    protected $authorization;
    protected $uid;

    public function __construct($mch_config){
        if (empty($mch_config['app_id'])){
            throw new InvalidArgumentException("缺少配置文件app_id");
        }
        if (empty($mch_config['app_secret'])){
            throw new InvalidArgumentException("缺少配置文件app_secret");
        }
        if (empty($mch_config['mobile'])){
            throw new InvalidArgumentException("缺少配置文件mobile");
        }
        $this->app_id        = $mch_config['app_id'];
        $this->app_secret    = $mch_config['app_secret'];
    }

    //通过密钥和app_id获取access_token
    public function getOauthAccessToken(){
        $access_token = Cache::getCache('mch_api_access_token'.$this->app_id);//获取缓存中的access_token
        $uid          = Cache::getCache('mch_api_uid'.$this->app_id);
        if ($access_token && $uid){//如果当前有access_token，直接返回access_token
            $this->uid  = $uid;
            return $this->access_token = $access_token;
        }
        $refresh_token = Cache::getCache('mch_api_refresh_token'.$this->app_id);
        if ($refresh_token){//如果有refresh_token，置换access_token
            $token_data = $this->getOauthRefreshToken($refresh_token);
            if ($token_data){
                $json = json_decode($token_data,true);
                if ($json['code']==200){//说明刷新access_token成功
                    Cache::setCache('mch_api_access_token'.$this->app_id,$token_data['data']['access_token'],60*60*24*20);
                    Cache::setCache('mch_api_uid'.$this->app_id,$token_data['data']['uid'],60*60*24*20);
                    $this->uid   = $token_data['data']['uid'];
                    return $this->access_token = $token_data['data']['access_token'];
                }else{
                    throw new InvalidArgumentException($json['message']);
                }
            }
            return false;
        }else{//重新获取access_token和refresh_token
            $data = [
                'app_id'    => $this->app_id,
                'timestamp' => time(),//当前时间戳
                'nonce'     => Tools::createNoncestr(),//生成随机字符串
            ];
            $sign           = Tools::getSign($data,$this->app_secret);
            $data['sign']   = $sign;
            $result         = Tools::httpPost(self::DOMAIN_NAME.self::OAUTH_TOKEN_URL,$data);
            if ($result){
                $json = json_decode($result,true);
                if ($json['code']===200){//说明当前请求成功
                    Cache::setCache('mch_api_access_token'.$this->app_id,$json['data']['access_token'],60*60*24*20);//设置20天的缓存时间
                    Cache::setCache('mch_api_refresh_token'.$this->app_id,$json['data']['refresh_token'],60*60*24*300);//设置300天的缓存时间
                    Cache::setCache('mch_api_uid'.$this->app_id,$json['data']['uid'],60*60*24*20);
                    $this->uid = $json['data']['uid'];
                    return $this->access_token  = $json['data']['access_token'];
                }else{//抛出异常信息
                    throw new InvalidArgumentException($json['message']);
                }
            }
            return false;
        }
    }

    //刷新access_token并续期
    public function getOauthRefreshToken($refresh_token){
        $result = Tools::httpGet(self::DOMAIN_NAME.self::OAUTH_REFRESH_URL."?app_id=$this->app_id&refresh_token=$refresh_token");
        if ($result){
            $json = json_decode($result,true);
            return $json;
        }
        return false;
    }

    //获取请求的header请求头内容
    public function getAuthorization(){
        if (!$this->getOauthAccessToken()){
            return false;
        }
        $authorization = [
                'authorization:USERID '.base64_encode($this->app_id.':'.$this->access_token.':'.$this->uid),
        ];
        return $this->authorization = $authorization;
    }

}
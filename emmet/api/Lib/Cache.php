<?php
namespace emmet\api\Lib;
// +----------------------------------------------------------------------
// | 作  者: LiuChuang
// +----------------------------------------------------------------------
// | 日  期: 2021/9/1 0001 9:21
// +----------------------------------------------------------------------
// | 备  注: 缓存类
// +----------------------------------------------------------------------

use emmet\api\Exception\LocalCacheException;

class Cache{

    /**缓存路径
     * @var null
     */
    public static $cache_path = null;

    /*
     * 缓存前缀
     */
    public static $cache_prefix = 'emmet_api_';

    /**缓存写入操作
     * @var array
     */
    public static $cache_callable = [
        'set' => null,//写入缓存
        'get' => null,//获取缓存
        'del' => null,//删除缓存
        'put' => null,//写入文件
    ];

    /**
     *
     * @param $name 缓存名称
     * @param $value 缓存内容
     * @param int $expired 缓存时间 0 代表永久缓存
     * @descripton 设置缓存
     */
    public static function setCache($name,$value,$expired = 3600){
        if (is_callable(self::$cache_callable['set'])){
            return call_user_func_array(self::$cache_callable['set'],func_get_args());
        }
        $file = self::_getCacheName($name);
        $data = [
            'name' => $name,
            'value' => $value,
            'expired' => time()+intval($expired)
        ];
        if (!file_put_contents($file,serialize($data))){
            throw new LocalCacheException("本地缓存失败");
        }
        return $file;
    }

    /**
     *
     * @descripton 获取缓存内容
     * @param $name 缓存名称
     * @return false|mixed|null
     */
    public static function getCache($name){
        if (is_callable(self::$cache_callable['get'])){
            return call_user_func_array(self::$cache_callable['get'],func_get_args());
        }
        $file = self::_getCacheName($name);
        if (file_exists($file) && is_file($file) && ($content = file_get_contents($file))){
            $data = unserialize($content);
            if (isset($data['expired']) && (intval($data['expired']) === 0 || intval($data['expired']) > time())){
                return $data['value'];
            }
            self::delCache($name);
        }
        return null;
    }

    /**
     *
     * @descripton 删除缓存
     * @param $name 缓存名称
     * @return bool|mixed
     */
    public static function delCache($name){
        if (is_callable(self::$cache_callable['del'])){
            return call_user_func_array(self::$cache_callable['del'],func_get_args());
        }
        $file = self::_getCacheName($name);
        return file_exists($file)?unlink($file):true;
    }

    /**
     *
     * @descripton 应用缓存目录
     * @param $name
     * @return string
     */
    public static function _getCacheName($name){
        if (empty(self::$cache_path)){
            self::$cache_path = dirname(__DIR__).DIRECTORY_SEPARATOR."Cache".DIRECTORY_SEPARATOR;
        }
        file_exists(self::$cache_path) || mkdir(self::$cache_path,0755,true);
        return self::$cache_path.self::$cache_prefix.$name;
    }
    
}
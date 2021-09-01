<?php
namespace emmet\api;
// +----------------------------------------------------------------------
// | 作  者: LiuChuang
// +----------------------------------------------------------------------
// | 日  期: 2021/8/27 0027 16:34
// +----------------------------------------------------------------------
// | 备  注: 入口文件，实例化的话直接调用此文件即可
// +----------------------------------------------------------------------

class Loader{

    protected $type;
    protected $config;
    public function __construct($type,$config){
        $this->type   = $type;
        $this->config = $config;
    }


    public function __call($method, $arguments){
        $class_name = '\\emmet\\api\\Emt'.ucfirst($this->type);
        if (!class_exists($class_name)){
            return false;
        }
        $instance = new $class_name($this->config);
        if (!method_exists($instance,$method)){
            return false;
        }
        return call_user_func_array([$instance,$method],$arguments);
    }


}
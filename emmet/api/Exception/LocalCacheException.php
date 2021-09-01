<?php
namespace emmet\api\Exception;
// +----------------------------------------------------------------------
// | 作  者: LiuChuang
// +----------------------------------------------------------------------
// | 日  期: 2021/9/1 0001 10:18
// +----------------------------------------------------------------------
// | 备  注: 本地缓存异常
// +----------------------------------------------------------------------

class LocalCacheException extends \Exception {

    /**
     * @var array
     */
    public $raw = [];

    /**
     * LocalCacheException constructor.
     * @param $message
     * @param int $code
     * @param array $raw
     */
    public function __construct($message,$code = 0,$raw = []){
        parent::__construct($message,(int)$code);
        $this->raw = $raw;
    }

}
<?php
namespace emmet\api\Exception;
// +----------------------------------------------------------------------
// | 作  者: LiuChuang
// +----------------------------------------------------------------------
// | 日  期: 2021/9/1
// +----------------------------------------------------------------------
// | 备  注: 
// +----------------------------------------------------------------------

/**
 * 接口参数异常
 */
class InvalidArgumentException extends \InvalidArgumentException{

    /**
     * @var array
     */
    public $raw = [];

    /**
     * InvalidArgumentException constructor.
     * @param $message
     * @param int $code
     * @param array $raw
     */
    public function __construct($message,$code=0,$raw=[]){
        parent::__construct($message,(int)$code);
        $this->raw = $raw;
    }


}
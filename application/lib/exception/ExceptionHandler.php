<?php

namespace app\lib\exception;

use think\exception\Handle;//继承tp5自动异常处理类
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{

    private $code;
    private $msg;
    private $errorCode;

    //重写错误返回的格式 重写父类的render方法 该方法在异常抛出时会自动执行
    public function render(\Exception $e)
    {
        if($e instanceof BaseException){//如果是自定义的异常

            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;

        }else{//如果是think\Exception异常

            if(config('app_debug')){//返回默认错误页面（出错时tp自带的默认错误页面）

                //调用父类的render方法
                return parent::render($e);
            }else{

                $this->code = 500;
                $this->msg = '服务器内部错误，不想告诉你';
                $this->errorCode = 999;//需要记录在api文档里说明
                $this->recordErrorLog($e);
            }
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()//返回客户端当前请求的url路径
        ];
        return json($result,$this->code);
    }

    private function recordErrorLog(\Exception $e)
    {
        //初始化日志
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error']//错误级别，之后用到的级别只有高于error才能被记录
        ]);

        //param：错误信息 错误级别
        Log::record($e->getMessage(),'error');
    }
}
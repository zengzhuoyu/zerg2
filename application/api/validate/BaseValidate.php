<?php

namespace app\api\validate;

use think\Request;
use think\Validate;
use app\lib\exception\ParameterException;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        //获取所有的http传入参数
        $request = Request::instance();
        $params = $request->param();

        //批量验证
        $result = $this->batch()->check($params);
        if(!$result){

            $e = new ParameterException([
                'msg' => $this->error
            ]);

            throw $e;
        }else{
            return true;
        }
    }

    protected function isPositiveInteger($value,$rule = '',$data = '',$field = '')
    {
        //验证正整数规则
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }

        //自定义错误返回信息
        return false;
    }
}
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
}
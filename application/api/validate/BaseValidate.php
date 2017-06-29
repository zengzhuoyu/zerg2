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

    public function isNotEmpty($value,$rule = '',$data = '',$field = '')
    {
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }

    public function getDataByRule($array)
    {
        if(array_key_exists('user_id',$array) || array_key_exists('uid',$array)){
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }

        $newArray = [];
        foreach($this->rule as $key => $value){
            $newArray[$key] = $array[$key];
        }

        return $newArray;
    }

    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
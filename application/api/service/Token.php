<?php

namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\ParameterException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    //生成自定义格式的Token
    public static function generateToken()
    {
        //32个字符组成一组随机字符串
        $randChars = getRandChar(32);

        //自定义规则加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //salt 盐
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        $scope = self::getCurrentTokenVar('scope');
        if($scope == ScopeEnum::Super){
            // 只有Super权限才可以自己传入uid
            // 且必须在get参数中，post不接受任何uid字段
            $userID = input('get.uid');
            if(!$userID){
                throw new ParameterException([
                    'msg' => '没有指定需要操作的用户对象'
                ]);
            }

            return $userID;
        }else{
            return $uid;
        }
    }

    //获得相应缓存信息
    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()
            ->header('token');

        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }

    //用户专有权限 exclusive：专有
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope == ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    //用户、管理员权限
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    //检测当前用户 是否等于 订单的下单用户user_id
    public static function isValidOperate($checkUID)
    {
        if(!$checkUID){
            throw new Exception('检查UID时必须传入一个被检查的UID');
        }

        $currentOperateUID = self::getCurrentUid();
        if($currentOperateUID == $checkUID){
            return true;
        }
        return false;
    }

}
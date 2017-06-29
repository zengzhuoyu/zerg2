<?php

namespace app\api\service;

use think\Exception;
use app\lib\exception\WeChatException;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
            $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);//返回的是字符串的json类型
        $wxResult = json_decode($result,true);

        //微信返回的结果如果是错误的，结果会为空
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }else{
            //返回错误，会存在一个参数errcode
            $loginFail = array_key_exists('errorcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);
            }

        }
    }

    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }

    private function grantToken($wxResult)
    {
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);

        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }

        //缓存
        $cacaheValue = $this->prepareCacheValue($wxResult,$uid);
        $token = $this->saveToCache($cacaheValue);

        return $token;
    }

    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);

        return $user->id;
    }

    private function prepareCacheValue($wxResult,$uid)
    {
        $cacaheValue = $wxResult;
        $cacaheValue['uid'] = $uid;
        $cacaheValue['scope'] = ScopeEnum::User;

        return $cacaheValue;
    }

    //写入缓存
    private function saveToCache($cacaheValue)
    {
        $key = self::generateToken();
        $value = json_encode($cacaheValue);//数组转换成json字符串

        //缓存失效时间作为 令牌失效时间
        $expire_in = config('setting.token_expire_in');

        //使用tp5自带缓存 写入缓存
        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }

        return $key;
    }
}

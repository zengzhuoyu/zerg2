<?php

namespace app\api\controller\v1;

use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token
{
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();

        $ut = new UserToken($code);
        $token = $ut->get();

        return [
            'token' => $token//不要以字符串返回给客户端
        ];
    }
}

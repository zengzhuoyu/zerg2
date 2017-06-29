<?php

namespace app\api\model;

use think\Model;

class User extends Model
{
    public function address()
    {
        return $this->hasOne('UserAddress','user_id','id');
    }

    public static function getByOpenID($openid)
    {
        $user = self::where('openid','=',$openid)
            ->find();

        return $user;
    }
}

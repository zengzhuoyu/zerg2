<?php

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;

class Banner
{
    public function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();
    }
}
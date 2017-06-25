<?php

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
//use app\lib\exception\BannerMissException;
use think\Exception;

class Banner
{
    public function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $banner = BannerModel::getBannerById($id);

        if(!$banner){
//            throw new BannerMissException();
            throw new Exception('内部错误');
        }

        return $banner;
    }
}
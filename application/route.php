<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//获取指定id的banner信息
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner',[],['id' => '\d+']);
//1 banner
//多 banner_item

//1 image
//1 banner_item

//BANNER有多个banner_item(有一个image)

//所有专题
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
//1 image
//1 THEME

//专题详情
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne',[],['id' => '\d+']);
//多 theme
//多 product
//theme_product

//1 image
//1 theme

//3个THEME(有一个image)拥有多个product

//商品 - 路由分组
Route::group('api/:version/product',function(){//参数二是闭包函数

    //分类中的商品
    Route::get('/by_category','api/:version.Product/getAllInCategory');
    //1 category
    //多 PRODUCT

    //商品详情
    Route::get('/:id','api/:version.Product/getOne',[],['id' => '\d+']);
//    1 PRODUCT
//    多 product_image order排序

//    1 image
//    1 product_image

//    1 product
//    多 product_property

//    一个商品有多个图片,多个属性

    //最近新品
    Route::get('/recent','api/:version.Product/getRecent');

});

//所有分类
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');
//1 image
//1 CATEGORY

Route::post('api/:version/token/user','api/:version.Token/getToken');

Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');

//下单
Route::post('api/:version/order','api/:version.Order/placeOrder');

//预支付与吊起支付二维码进行支付
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');

Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');

//用户历史订单数据
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');

//订单详情
Route::get('api/:version/order/:id', 'api/:version.Order/getDetail',[], ['id'=>'\d+']);









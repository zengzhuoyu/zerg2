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
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
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
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');
//多 theme
//多 product
//theme_product

//1 image
//1 theme

//3个THEME(有一个image)拥有多个product

//最近新品
Route::get('api/:version/product/recent','api/:version.Product/getRecent');
//分类中的商品
Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
//1 category
//多 PRODUCT

//所有分类
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');
//1 image
//1 CATEGORY





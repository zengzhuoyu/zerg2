<?php

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    public function getAllCategories()
    {
        $categories = CategoryModel::all([],'img');

        //换一种写法
//        $categories = CategoryModel::with('img')
//            ->select();

        if($categories->isEmpty()){
            throw new CategoryException();
        }

        return $categories;
    }
}

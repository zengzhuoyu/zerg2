<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\exception\UserException;
use app\lib\exception\SuccessMessage;

class Address extends BaseController
{
    protected $beforeActionList = [
        //设置前者是后者的前置操作
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];

    public function createOrUpdateAddress()
    {
        //根据用户访问受保护的接口必须要携带的令牌去缓存中查找到相应的uid
        $validate = new AddressNew();
        $validate->goCheck();

        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }

        $dataArray = $validate->getDataByRule(input('post.'));

        $userAddress = $user->address;//查出模型方式调用关联模型

        if(!$userAddress){

            //通过模型的关联来新增数据 直接更新
            $user->address()->save($dataArray);
        }else{
            //通过模型的关联来更新数据 先查出来再更新
            $user->address->save($dataArray);
        }

//        //把新的模型返回给客户端：rest标准做法
//        return $user;
        //或者 只告诉客户端是否新增、更新成功
//        return 'success';
        //或者
        return json(new SuccessMessage(),201);

    }
}

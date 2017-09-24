<?php

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\LoginFrom;
use frontend\models\Member;
use frontend\models\SmsDemo;
use yii\web\ForbiddenHttpException;

class MemberController extends \yii\web\Controller
{
public $enableCsrfValidation=false;
    public function actionRegister(){
        $model=new Member();
//        $model->scenario=Member::SCENARIO_ADD;
        $request=\Yii::$app->request;
        if ($request->isPost){
            //因为视图的字段不是数组，而以前的视图echo的字段是一个数组
            $model->load($request->post(),'');
//            var_dump($model);exit;
            if($model->validate()){
//                var_dump($model);exit;
                $model->save(false);
                return $this->redirect(['login']);
            }else{
                $model->getErrors();
            }
        }
        //显示视图不加载视图文件
        return $this->renderPartial('register',['model'=>$model]);
    }
//    ajax验证用户唯一性
    public function actionValidateUser(){
        $username=\Yii::$app->request->get('username');
        if(Member::findOne(['username'=>$username])){
            return 'false';
        }
        return 'true';
    }
    //前台验证
//    public function actionCheckUser(){
//        $username=\Yii::$app->request->get('username');
//        if(Member::findOne(['username'=>$username])){
//            return 'false';
//        }
//        return 'true';
//    }
    //登录
    public function actionLogin(){
        $model=new LoginFrom();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
//            var_dump($model);exit;
            if($model->validate()){
                if($model->login()){
                        //用户登录后，获取cookie的值，保存到数据库
                    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    $cookies = \Yii::$app->request->cookies;
                    $value = $cookies->getValue('carts');
        //   var_dump($value);exit;
                    if($value){
                        //反序列化cookie值(是字符串),转成数组，因为最开始把传的$goods_id值作为键，amount作为值存到了数组中
                        $carts = unserialize($value);
                        foreach($carts as $goods_id_cookie=>$amount){
                            //检查数据表中是否有该商品
                            $cart = Cart::findOne(['goods_id'=>$goods_id_cookie,'member_id'=>\Yii::$app->user->id]);
                            if($cart){
                                //如果有就更新该商品的数量
                                $cart->amount+=$amount;
                                $cart->save();
                            }else{
                                //没有，就添加该数据
                                $model=new Cart();
                                $model->amount=$amount;
                                $model->member_id=\Yii::$app->user->identity->id;
                                $model->goods_id=$goods_id_cookie;
                                $model->save(false);

                            }
                        }
                        //清除购物车cookie
                       \Yii::$app->response->cookies->remove('carts');
                    }
                    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    return $this->redirect(['list/index']);
                }else{
                    throw new ForbiddenHttpException('用户名或密码错误');
                }
            }else{
                $model->getErrors();
            }
        }
        return $this->renderPartial('login',['model'=>$model]);
}
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect('login');
    }
    //测试是否登录
    public function actionUser(){
        //当前用户的身份实例，验证是否为游客
        $identity=\Yii::$app->user->isGuest;
        var_dump($identity);
    }
    //显示收货地址
    public function actionAddress(){
        $models=Address::find()->all();
//        var_dump($models);exit;
        return $this->renderPartial('address',['models'=>$models]);
    }
    //添加收货地址
    public function actionAdd(){
        $model=new Address();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
//            var_dump($model->name);exit;
            $model->member_id=\Yii::$app->user->identity->id;
            if($model->validate()){
                $model->save(false);
                return $this->redirect(['member/address']);
            }else{
                var_dump($model->getErrors());
            }
        }
//        return $this->
    }
    //修改收货地址
    public function actionEditAddress($id){
        $address=Address::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $address->load($request->post(),'');
            if($address->validate()){
                $address->save(false);
                return $this->redirect(['member/address']);
            }else{
                var_dump($address->getErrors());
            }
        }
        return $this->renderPartial('edit-address',['address'=>$address]);
    }
    //删除收货地址
    public function actionDelAdderss(){
        $id = \Yii::$app->request->post('id');
//        var_dump($id);exit;
        $model=Address::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }
    //测试发送短信
    public function actionSms(){
        $phone=\Yii::$app->request->post('phone');
        $code=rand(1000,9999);
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('code_'.$phone,$code);
       /* $demo = new SmsDemo(
            "LTAIBd425eGhePJ8",
            "DRmcaPxVXYhXNlF2BuPdsEhqy867fS"
        );
//
//        echo "SmsDemo::sendSms\n";
        $response = $demo->sendSms(
            "芒果汤圆", // 短信签名
            "SMS_97975009", // 短信模板编号
            "18184740040", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>rand(1000,9999),
//                "product"=>$code
            )
        );*/
//        print_r($response);
      /*if($response->Message=='OK'){
            echo '发送成功';
      }else{
          echo '发送失败';
      }*/
      echo $code;
    }
    //验证短信
    public function actionValidateSms($sms,$phone){
        //从redis中取出值
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $code=$redis->get('code_'.$phone);
        if($code==null|| $code!=$sms){
            return "false";
        }
        return "true";
    }
    //测试redi操作
    public function actionRedis(){
        $redis=new \Redis();
        //连接
        $redis->connect('127.0.0.1');
        $redis->set('name','张三');
        echo 'OK';
    }

}

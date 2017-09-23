<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use yii\base\Object;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;

class ListController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    //商品分类列表的显示
    public function actionIndex()
    {
        $categories=GoodsCategory::find()->andwhere(['parent_id'=>0])->all();
        return $this->renderPartial('index',['categories1'=>$categories/*,'sons'=>$sons*/]);
    }
    //根据a标签查询子分类
    public function actionShow()
    {
        $category_id=\Yii::$app->request->get('id');
        //根据$category_id查询商品分类  列表显示有分页
        $category=GoodsCategory::findOne(['id'=>$category_id]);
        $query=Goods::find();
        //考虑点击一级 二级 三级的情况
        if($category->depth==2){//三级分类
            //根据传过来的id，查找商品的goods_category_id 从而查到商品
           $query->andWhere(['goods_category_id'=>$category_id]);
        }else{//一二级分类是一样的，都查找三级分类的数据
            //假设$category_id = 5 对应的子分类为（3级分类）ID  7 8
            $ids=$category->children()->select('id')->andWhere(['depth'=>2])->column();//查找商品深度为2的所有的分类取第一个字段id
            //select *  from goods where goods_category_id  in (7,8)
            $query->andWhere(['in','goods_category_id',$ids]);
        }
        //分页工具条
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->defaultPageSize = 2;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
//        var_dump($model);exit;
        return $this->renderPartial('show',['models'=>$model,'pager'=>$pager]);
    }
    public function actionSon(){
        $id=\Yii::$app->request->get('id');
        $goods=Goods::findOne(['id'=>$id]);
        $gallerys=GoodsGallery::find()->where(['goods_id'=>$goods->id])->all();
//        var_dump($gallerys);exit;
        $intro=GoodsIntro::findOne(['goods_id'=>$goods->id]);
        return $this->renderPartial('goods',['goods'=>$goods,'gallerys'=>$gallerys,'intro'=>$intro]);
    }
    //添加到购物车页面 完成添加到购物车
    public function actionAddtocart($goods_id,$amount){
//        var_dump($amount);exit;
       //判断是否登录
        if(\Yii::$app->user->isGuest){
            //从cookie中取值,判断cookie是否有值
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
//            var_dump($value);exit;
            if($value){
                //反序列化cookie值(是字符串),转成数组，因为最开始把传的$goods_id值作为键，amount作为值存到了数组中
                $carts = unserialize($value);
//                var_dump($carts);exit;
            }else{
                $carts = [];
            }
            //判断购物车里是否有当前添加的商品
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id] += $amount;
            }else{
                $carts[$goods_id] = intval($amount);
            }
            //写入cookie
            /*$carts = [
                    1=>2,2=>10
            ];*/
            //设置当前添加商品的数据放入cookie中
            $cookies =\Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookie->expire = time()+7*24*3600;//过期时间戳
            $cookies->add($cookie);
//            var_dump($cookie);exit;
        }else {
            //检查商品数据是否存在
            $cart = Cart::find()->where(['and',['goods_id'=>$goods_id],['member_id'=>\Yii::$app->user->getId()]])->one();
//            $cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
//            var_dump($cart);exit;
            if ($cart==null){
                //数据库中没有这个购物车记录
                $model=new Cart();
                $model->load(\Yii::$app->request->get(),'');
                $model->member_id=\Yii::$app->user->identity->id;
//            var_dump($model->load(\Yii::$app->request->get(),''));exit;
                $model->save();
            }else{
                //购物车中已经有了这个商品记录
                $cart->amount+=$amount;
                $cart->save();
            }

        }
//        //直接跳转到购物车
        return $this->redirect(['cart']);
    }
    //购物车页面
    public function actionCart(){
        //获取购物车的数据
        if(\Yii::$app->user->isGuest){
            //从cookie
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);//$carts = [1=>2,2=>10]
            }else{
                $carts = [];
            }
            //查询商品
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
        }else{
            //根据查询数据库购物车中的
            $cart = ArrayHelper::map(Cart::find()->where(['member_id'=>\Yii::$app->user->id])->asArray()->all(),'goods_id','amount');
            $models = [];
            foreach($cart as $goods_id=>$amount){
                $goods = Goods::findOne(['id'=>$goods_id]);//获得商品信息
//                var_dump($goods);exit;
                if($goods){
                    $models[]=$goods;
                }
            }
            $carts = $cart;
        }

//        var_dump($count_price);exit;
        return $this->renderPartial('cart',['models'=>$models,'carts'=>$carts]);
    }
    //ajax修改商品数量
    public function actionAjax($way){

        if($way=='edit'){
            //获取数据
            $goods_id=\Yii::$app->request->post('goods_id');
//        var_dump($goods_id);exit;
            $amount=\Yii::$app->request->post('amount');
            if(\Yii::$app->user->isGuest){
                //获取cookie的值
                $cookies=\Yii::$app->request->cookies;
                $value=$cookies->getValue('carts');
//            var_dump($value);exit;
                if($value){
                    $carts=unserialize($value);
                }else{
                    $carts=[];
                }
                //判断购物车里是否有当前添加的商品
                if(array_key_exists($goods_id,$carts)){
                    //有就直接赋值
                    $carts[$goods_id]=$amount;
                }
                //设置当前添加商品的数据放入cookie中
                $cookies=\Yii::$app->response->cookies;
                //实例化cookie，保存cookie
                $cookie=new Cookie();
                $cookie->name='carts';
                $cookie->value= serialize($carts);
                $cookie->expire=time()*7*24*3600;
                $cookies->add($cookie);
            }else{
                $cart=Cart::findOne(['member_id'=>\Yii::$app->user->identity->id,'goods_id'=>$goods_id]);
                $cart->amount=$amount;
                $cart->save();
            }
          return 'success';
        }elseif ($way=='del'){
            $goods_id = \Yii::$app->request->post('goods_id');
            if(\Yii::$app->user->isGuest){
                $cookies = \Yii::$app->request->cookies;
                $value = $cookies->getValue('carts');
                if($value){
                    $carts = unserialize($value);//$carts = [1=>2,2=>10]
                }else{
                    $carts = [];
                }
                //删除购物车中该id对应的商品
                unset($carts[$goods_id]);
                $cookies=\Yii::$app->response->cookies;
                //实例化cookie
                $cookie=new Cookie();
                $cookie->name='carts';
                $cookie->value= serialize($carts);
                $cookie->expire=time()*7*24*3600;
                $cookies->add($cookie);
            }else{
                $model=Cart::findOne(['id'=>$goods_id]);
                $model->delete();
            }
            return 'success';
        }
    }
    //订单页面
   public function actionOrder(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else{
//            var_dump(\Yii::$app->user->identity->id);exit;
            $address=Address::find()->where(['member_id'=>\Yii::$app->user->identity->id])->all();
            $cart=Cart::findOne(['member_id'=>\Yii::$app->user->identity->id]);
            return $this->renderPartial('order',['address'=>$address]);
        }
   }

}

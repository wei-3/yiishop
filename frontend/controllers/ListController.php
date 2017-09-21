<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class ListController extends \yii\web\Controller
{
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
        return $this->renderPartial('show',['models'=>$model,'pager'=>$pager]);
    }
    public function actionSon(){
        $goods_category_id=\Yii::$app->request->get('id');
        $goods=Goods::findOne(['goods_category_id'=>$goods_category_id]);
        $gallerys=GoodsGallery::find()->where(['id'=>$goods->id])->all();
//        var_dump($gallerys);exit;
        $intro=GoodsIntro::findOne(['goods_id'=>$goods->id]);
        return $this->renderPartial('goods',['goods'=>$goods,'gallerys'=>$gallerys,'intro'=>$intro]);
    }
}

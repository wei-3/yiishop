<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use flyok666\qiniu\Qiniu;

class ArticleController extends \yii\web\Controller
{
    //显示页面
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAdd(){
        $model_article=new Article();
        $model_detail=new ArticleDetail();
        return $this->render('add',['model_article'=>$model_article,'model_detail'=>$model_detail]);
    }
    //添加时间行为
    public function behaviors()
    {
        return [
            [
                'class'=>TimestampBehavior::className(),
                ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
            ]
        ];
    }
}

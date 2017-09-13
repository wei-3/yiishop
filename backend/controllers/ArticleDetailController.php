<?php

namespace backend\controllers;

use backend\models\ArticleDetail;

class ArticleDetailController extends \yii\web\Controller
{
    public function actionIndex($id)
    {
        $model=ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('index',['model'=>$model]);
    }
}

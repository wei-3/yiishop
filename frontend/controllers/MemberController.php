<?php

namespace frontend\controllers;

class MemberController extends \yii\web\Controller
{

    public function actionRegister(){
        //显示视图不加载视图文件
        return $this->renderPartial('register');
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

}

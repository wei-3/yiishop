<?php
namespace backend\models;
use yii\base\Model;

class CheckpwdForm extends Model{
    public $old_pwd;
    public $new_pwd;
    public $re_pwd;
    public function rules()
    {
        return [
            [['old_pwd','new_pwd','re_pwd'],'required'],
//            ['re_pwd','compare','compareAttribute'=>'new_pwd','operator'=>'!=='],operator是比较的属性，compare默认是==
            ['re_pwd','compare','compareAttribute'=>'new_pwd','message'=>'两次密码必须一致'],
            //自定义验证规则
            ['old_pwd','validatePwd']
        ];
    }
    //自定义验证方法 只考虑验证不通过的情况
    public function validatePwd(){
// \Yii::$app->user->identity =====  $model_admin=Admin::findOne(['id'=>\Yii::$app->user->id])
        if(!\Yii::$app->security->validatePassword($this->old_pwd,\Yii::$app->user->identity->password_hash)){
            $this->addError('old_pwd','旧密码不正确');
        }
    }
}

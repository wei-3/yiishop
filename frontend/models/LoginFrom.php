<?php
namespace frontend\models;
use yii\base\Model;

class LoginFrom extends Model{
    public $username;
    public $password;
    public $remember;
    public $checkcode;
    public function rules(){
        return [
            //姓名不能为空 年龄不能为空
            [['username','password'],'required'],
            ['remember','string'],
            ['checkcode','captcha','captchaAction'=>'site/captcha','message'=>'验证码错误'],
        ];
    }
    public function login(){
        $username=Member::findOne(['username'=>$this->username]);
        if($username!=null){
            if(\Yii::$app->security->validatePassword($this->password,$username->password_hash)){
                //如果判断成功就返回并保存该数据
                //获取最后登录的ip
                $username->last_login_ip=\Yii::$app->request->userIP;
//               var_dump($ip);exit;
                //获取最后登录时间
                $username->last_login_time=time();
                $username->save(false);
                if($this->remember){
                    return \Yii::$app->user->login($username,7*24*3600);
                }
                    return \Yii::$app->user->login($username);

            }
            $this->getErrors('密码错误');
        }else{
            $this->getErrors('账户不存在');
        }
        return false;
    }
}

<?php
namespace backend\models;
use yii\base\Model;

class LoginFrom extends Model{
    public $username;
    public $password;
    public $remember;
    public function rules(){
        return [
            //姓名不能为空 年龄不能为空
            [['username','password'],'required'],
            ['remember','integer'],
        ];
    }
    //定义字段的标签名称
    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'remember'=>'记住我'
        ];
    }
   public function login(){

        $info=Admin::findOne(['username'=>$this->username]);
        //var_dump($info);exit;
        //判断是否有该用户名
       if($info!==null){
           //有该用户就判断密码是否正确
           if(\Yii::$app->security->validatePassword($this->password,$info->password_hash)){
               //如果判断成功就返回并保存该数据
               //获取最后登录的ip
               $info->last_login_ip=\Yii::$app->request->userIP;
//               var_dump($ip);exit;
               //获取最后登录时间
               $info->last_login_time=time();
//               var_dump($info->last_login_time);exit;
               $info->save(false);
//               var_dump($info);exit;
               if($this->remember){
                   return \Yii::$app->user->login($info,7*24*3600);
               }
               return \Yii::$app->user->login($info);
           }
           $this->addError('password','密码不正确');
       }else{
           $this->addError('username','账户不存在');
       }
       return false;
   }
}

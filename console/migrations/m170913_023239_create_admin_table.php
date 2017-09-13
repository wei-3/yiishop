<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170913_023239_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->comment('用户名'),
            'auth_key' => $this->string(32),
            'password_hash' => $this->string()->comment('密码'),
            'password_reset_token' => $this->string(),
            'email' => $this->string()->comment('邮箱'),

            'status' => $this->smallInteger()->comment('状态'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'last_login_time'=>$this->integer()->comment('最后登录时间'),
            'last_login_ip'=>$this->integer()->comment('最后登录时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}

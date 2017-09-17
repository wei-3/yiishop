<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170917_060515_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(100)->notNull()->comment('名称'),
            'parent_id'=>$this->string(100)->notNull()->comment('上级菜单'),
            'url'=>$this->string(100)->notNull()->comment('地址/路由'),
            'sort'=>$this->integer(2)->notNull()->comment('排序')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}

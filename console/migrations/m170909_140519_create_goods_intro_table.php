<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m170909_140519_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            'goods_id' =>$this->integer()->comment('商品id'),
            'content' =>$this->text()->comment('商品描述'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}

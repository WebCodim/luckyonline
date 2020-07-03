<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m200628_225628_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string()->comment('Название категории')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }
}

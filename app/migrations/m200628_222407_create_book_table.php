<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book`.
 */
class m200628_222407_create_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book', [
            'id' => $this->bigPrimaryKey()->comment('ID'),
            'name' => $this->string()->comment('Название'),
            'cover' => "ENUM ('soft','hard') COMMENT 'Обложка'",
            'circulation' => $this->integer()->unsigned()->comment('Тираж')
        ]);

        $this->createIndex('book_cover_index', 'book', ['cover']);

        $this->createIndex('book_circulation_index', 'book', ['circulation']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('book');
    }
}

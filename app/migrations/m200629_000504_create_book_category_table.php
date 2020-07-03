<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book_category`.
 */
class m200629_000504_create_book_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book_category', [
            'book_id' => $this->bigInteger()->comment('Id книги'),
            'category_id' => $this->integer()->comment('Id категории'),
        ]);

        $this->addPrimaryKey('book_category_pk', 'book_category', ['book_id', 'category_id']);

        $this->addForeignKey(
            'book_fk',
            'book_category',
            'book_id',
            'book',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'category_fk',
            'book_category',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('book_fk', 'book_category');
        $this->dropForeignKey('category_fk', 'book_category');
        $this->dropTable('book_category');
    }
}

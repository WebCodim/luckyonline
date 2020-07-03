<?php

use yii\db\Migration;

/**
 * Handles the creation of table `surfer`.
 */
class m200629_064233_create_surfer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('surfer', [
            'datetime' => $this->dateTime()->comment('Время'),
            'status' => $this->tinyInteger(1)->comment('Статус')
        ]);

        $this->createIndex('surfer_datetime_index', 'surfer', ['datetime']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('surfer');
    }
}

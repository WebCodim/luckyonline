<?php

namespace app\models;

use Yii;
use PDO;

/**
 * This is the model class for table "surfer".
 * @property string|null $datetime Время
 * @property int|null $status Статус
 */
class Surfer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'surfer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datetime'], 'safe'],
            [['status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'datetime' => 'Время',
            'status' => 'Статус',
        ];
    }

    /**
     * {@inheritdoc}
     * @return SurferQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SurferQuery(get_called_class());
    }

    /**
     * @param string $startDate
     * @param string $endDate
     *
     * @return int|null
     * @throws \yii\db\Exception
     */
    public function getMaxOnline(string $startDate, string $endDate): ?int
    {
        $currentOnline = (int) $this->getCurrentOnline($startDate);

        Yii::$app->db->createCommand("SET @online := :presetOnline;", [
            'presetOnline' => $currentOnline
        ])->execute();

        $result = Yii::$app->db->createCommand("
        SELECT
         MAX(online) as max,
         MIN(online) as min
        FROM (
           SELECT 
             `datetime`,
             `status`,
             (CASE
                 WHEN `status` = 1 THEN @online := @online + 1
                 WHEN `status` = 2 THEN @online := @online - 1
             END) as online
           FROM `surfer`
           WHERE `datetime` BETWEEN :startDate AND :endDate
           ORDER BY `datetime` ASC 
           ) as t;
        ", [
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->queryOne();

        $maxOnline = isset($result['max']) && is_string($result['max']) ? (int) $result['max'] : null;
        $minOnline = isset($result['min']) && is_string($result['min']) ? (int) $result['min'] : null;

        if ($maxOnline !== null && $minOnline !== null && $minOnline < 0) {
            // если значение minOnline принимает отрицалельное значение в какой-то момент,
            // $maxOnline нужно увеличить на его модуль
            $maxOnline = $maxOnline + abs($minOnline);
        }

        return $maxOnline;
    }

    /**
     * Функчия возвращает число пользователей онлайн на конкретное время, используя данные за 12 предыдущих часов.
     * Это повышает точность функции getMaxOnline на коротких интервалах.
     *
     * @param string $date
     *
     * @return int|null
     * @throws \yii\db\Exception
     */
    public function getCurrentOnline(string $date): ?int
    {
        $startDate = date('Y-m-d H:i:s', strtotime($date) - 12 * 60 * 60);
        $endDate = $date;

        Yii::$app->db->createCommand("SET @online := 0;")->execute();

        $result = Yii::$app->db->createCommand("
        SELECT
         @online as current,      
         MIN(online) as min
        FROM (
           SELECT 
             `datetime`,
             `status`,
             (CASE
                 WHEN `status` = 1 THEN @online := @online + 1
                 WHEN `status` = 2 THEN @online := @online - 1
             END) as online
         FROM `surfer`
         WHERE `datetime` BETWEEN :startDate AND :endDate
         ORDER BY `datetime` ASC 
        ) as t;
        ", [
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->queryOne();


        $currentOnline = isset($result['current']) && is_string($result['current']) ? (int) $result['current'] : null;
        $minOnline = isset($result['min']) && is_string($result['min']) ? (int) $result['min'] : null;

        if ($currentOnline !== null && $minOnline !== null && $minOnline < 0) {
            // если значение minOnline принимает отрицалельное значение в какой-то момент,
            // $currentOnline нужно увеличить на его модуль
            $currentOnline = $currentOnline + abs($minOnline);
        }

        return $currentOnline;
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 * @property int $id ID
 * @property string|null $name Название
 * @property string|null $cover Обложка
 * @property int|null $circulation Тираж
 * @property BookCategory[] $bookCategories
 * @property Category[] $categories
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cover'], 'string'],
            [['circulation'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'cover' => 'Обложка',
            'circulation' => 'Тираж',
        ];
    }

    /**
     * Gets query for [[BookCategories]].
     * @return \yii\db\ActiveQuery|BookCategoryQuery
     */
    public function getBookCategories()
    {
        return $this->hasMany(BookCategory::className(), ['book_id' => 'id']);
    }

    /**
     * Gets query for [[Categories]].
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('book_category', ['book_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return BookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BookQuery(get_called_class());
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getData1(): array
    {
        $data1 = Yii::$app->db->createCommand("
        SELECT
          b.id,
          b.name,
          b.cover,
          b.circulation,
          COUNT(bc.category_id) as categories
        FROM
          book b
        INNER JOIN
          book_category bc on b.id = bc.book_id
        WHERE
          b.cover = 'hard' AND b.circulation = 5000
        GROUP BY
          b.id
        HAVING
          categories > 3;
        ")->queryAll();

        return $data1;
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getData2(): array
    {
        $data2 = Yii::$app->db->createCommand("
        SELECT
          bc1.book_id as book_id1,
          bc2.book_id as book_id2,
          count(bc1.category_id) as categories
        FROM
          book_category bc1
        INNER JOIN
          book_category bc2 ON bc1.category_id = bc2.category_id AND bc1.book_id < bc2.book_id
        GROUP BY
          bc1.book_id, bc2.book_id
        HAVING
          categories >= 10;
        ")->queryAll();

        return $data2;
    }

}

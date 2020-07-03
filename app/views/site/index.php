<?php

/* @var $this yii\web\View */
/* @var $data1 array */
/* @var $data2 array */

/* @var $model \app\models\search\SurferSearch */

/* @var $maxOnline int|null */
/* @var $startOnline int|null */
/* @var $endOnline int|null */

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Welcome!';
?>
<div class="site-index">
    <h3>Задание 1</h3>
    <hr>

    <img src="/img/shema.png">

    <pre>
        <code>

        CREATE TABLE `book` (
        `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `name` varchar(255) DEFAULT NULL COMMENT 'Название',
        `cover` enum('soft','hard') DEFAULT NULL COMMENT 'Обложка',
        `circulation` int(11) unsigned DEFAULT NULL COMMENT 'Тираж',
        PRIMARY KEY (`id`),
        KEY `book_cover_index` (`cover`),
        KEY `book_circulation_index` (`circulation`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf-8

        CREATE TABLE `category` (
        `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `name` varchar(255) DEFAULT NULL COMMENT 'Название категории',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf-8

        CREATE TABLE `book_category` (
        `book_id` bigint(20) NOT NULL COMMENT 'Id книги',
        `category_id` int(11) NOT NULL COMMENT 'Id категории',
        PRIMARY KEY (`book_id`,`category_id`),
        KEY `category_fk` (`category_id`),
        CONSTRAINT `book_fk` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE CASCADE,
        CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=uft-8

        </code>
    </pre>

    <b>SQL 1:</b>
    <br><br>
    <pre>
        <code>

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
        </code>
    </pre>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $data1,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]),
        'columns' => [
            'id',
            'name',
            'cover',
            'circulation',
            'categories'
        ]
    ]);
    ?>
    <?php Pjax::end(); ?>

    <b>SQL 2:</b>
    <br><br>
    <pre>
        <code>
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
        </code>
    </pre>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $data2,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]),
        'columns' => [
            'book_id1',
            'book_id2',
            'categories'
        ]
    ]);
    ?>
    <?php Pjax::end(); ?>

    <h3>Задание 2</h3>
    <hr>

    <?php Pjax::begin(); ?>

    <div id="surfer-form">

        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'validationUrl' => Url::toRoute('site/validation'),
            'options' => [
                'data-pjax' => true
            ]
        ]); ?>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'startDate')->widget(DateTimePicker::class, [
                    'name' => 'startDate',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'options' => ['placeholder' => 'Date/time'],
                    'convertFormat' => true,
                    'value' => $model->startDate,
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd HH:i:s',
                        'autoclose' => true,
                        'weekStart' => 1,
                        'startDate' => $model->getMinDateTime(),
                        'endDate' => $model->getMaxDateTime(),
                        'todayBtn' => false,
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'endDate')->widget(DateTimePicker::class, [
                    'name' => 'endDate',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'options' => ['placeholder' => 'Date/time'],
                    'convertFormat' => true,
                    'value' => $model->endDate ?? $model->getDefaultMaxDateTime(),
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd HH:i:s',
                        'autoclose' => true,
                        'weekStart' => 1,
                        'startDate' => $model->getMinDateTime(),
                        'endDate' => $model->getMaxDateTime(),
                        'todayBtn' => false,
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-3">
                <label class="control-label">&nbsp;</label><br>
                <?= Html::submitButton('Show', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <?php if (isset($maxOnline)) : ?>
            <div class="alert alert-info" role="alert">
                Period: <?= Html::encode($model->startDate); ?> - <?= Html::encode($model->endDate); ?> <br>
                MaxOnline: <?= Html::encode($maxOnline); ?> <br>
                StartOnline: <?= Html::encode($startOnline); ?> <br>
                EndOnline: <?= Html::encode($endOnline); ?> <br>
            </div>
        <?php endif; ?>

    </div>

    <?php Pjax::end(); ?>
</div>

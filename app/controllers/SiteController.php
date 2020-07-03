<?php

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\search\SurferSearch;
use yii\widgets\ActiveForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $model = new SurferSearch();
        $maxOnline = null;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $maxOnline = $model->getMaxOnline($model->startDate, $model->endDate);
            $startOnline = $model->getCurrentOnline($model->startDate);
            $endOnline = $model->getCurrentOnline($model->endDate);
        }

        return $this->render('index', [
            'data1' => Book::getData1(),
            'data2' => Book::getData2(),
            'model' => $model,
            'maxOnline' => $maxOnline,
            'startOnline' => $startOnline,
            'endOnline' => $endOnline,
        ]);
    }

    /**
     * @return Response
     */
    public function actionValidation()
    {
        $model = new SurferSearch();
        $model->load(Yii::$app->request->post());
        return $this->asJson(ActiveForm::validate($model));
    }
}

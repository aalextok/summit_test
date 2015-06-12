<?php

namespace frontend\controllers;

class PlaceController extends \yii\web\Controller
{
    public function actionChekin()
    {
        return $this->render('chekin');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}

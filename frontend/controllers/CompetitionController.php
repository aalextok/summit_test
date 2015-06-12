<?php

namespace frontend\controllers;

class CompetitionController extends \frontend\controllers\BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        return $this->render('view');
    }

}

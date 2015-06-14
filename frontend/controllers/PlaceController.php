<?php
namespace frontend\controllers;

class PlaceController extends \frontend\controllers\BaseController
{
    public function actionVisit()
    {
        $this->addLayoutClass("content", "congrats");
        return $this->render('visit');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}

<?php
namespace frontend\controllers;

class PlaceController extends \frontend\controllers\BaseController
{
    public function actionCheckin()
    {
        $this->addLayoutClass("content", "congrats");
        return $this->render('checkin');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}

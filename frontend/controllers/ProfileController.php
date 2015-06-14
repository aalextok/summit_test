<?php
namespace frontend\controllers;

use Yii;

class ProfileController extends \frontend\controllers\BaseController
{
    public function actionIndex()
    {
      
        $userId = Yii::$app->request->get('id');
        
        return $this->render('index');
    }
}

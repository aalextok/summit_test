<?php

namespace frontend\controllers;

use yii;
use backend\models\Competition;

class CompetitionController extends \frontend\controllers\BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        $cId = Yii::$app->request->get('id');
        $comp = Competition::findOne( ['id' => $cId] );
        
        return $this->render('view', [
          'competition' => $comp,
          'competition_id' => $cId
        ]);
    }

}

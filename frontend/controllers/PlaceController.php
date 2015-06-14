<?php
namespace frontend\controllers;

use \yii;
use \frontend\models\VisitForm;

class PlaceController extends \frontend\controllers\BaseController
{
    
    public function actionVisit()
    {
        $this->addLayoutClass("content", "congrats");
        
        $model = new VisitForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
          if ($model->saveVisit()) {
            Yii::$app->session->setFlash('success', 'Thank you for visiting this place. ');
          } else {
            Yii::$app->session->setFlash('error', 'There was an error.');
          }
          return $this->refresh();
        } else {
          return $this->render('visit', [
                'model' => $model,
          ]);
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}

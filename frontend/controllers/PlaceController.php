<?php
namespace frontend\controllers;

use \yii;
use \frontend\models\VisitForm;
use \backend\models\Place;
use backend\models\Activity;
use backend\models\Location;

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
    
    public function actionView()
    {
        $id = Yii::$app->request->get('id');
        $place = Place::findOne( ['id' => $id] );
        
        return $this->render('view', array(
            "place" => $place,
            "placeId" => $id
        ));
    }

    public function actionIndex()
    {
        $this->setPageTitle("Activities");
        
        $allActivities = Activity::find()->all();
        $allLocations = Location::find()->all();
        
        return $this->render('index', array(
            "allActivities" => $allActivities,
            "allLocations" => $allLocations
        ));
    }

}

<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

use yii\data\ActiveDataProvider;
use \yii\db\ActiveQuery;

class PlaceController extends ActiveController
{
    public $modelClass = 'backend\models\Place';
    public $enableCsrfValidation = false;
    
    public function actions(){
        $actions = parent::actions();
        
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }
    
    public function beforeAction($action) {
        Yii::$app->user->enableSession = false;
        return parent::beforeAction($action);
    }
    
    public function prepareDataProvider(){
        $model = new $this->modelClass;
//        
//        $query = new ActiveQuery($model);
//        $query->joinWith('activities')->where([]);
//        
//        $activity = ucfirst(strtolower(trim(Yii::$app->request->getQueryParam('activity'))));
//        
//        // TODO search by activity ids list?
//        
//        if(!empty($activity)){
//            $query->andWhere(['activity.name' => $activity]);
//        }
        
        $sql = "SELECT DISTINCT "
                . "place.id,"
                . "place.code,"
                . "place.name,"
                . "place.description,"
                . "place.meters_above_sea_level,"
                . "place.distance,"
                . "place.points,"
                . "place.latitude,"
                . "place.longtitude,"
                . "place.address,"
                . "place.location"
                . " FROM place";
        
        
        $activity = ucfirst(strtolower(trim(Yii::$app->request->getQueryParam('activity'))));
        if(!empty($activity)){
            $sql .= " INNER JOIN places_activities AS pa "
                    . "ON (place.id = pa.place_id) "
                    . "INNER JOIN activity "
                    . "ON (pa.activity_id = activity.id "
                    . "AND activity.name LIKE '$activity') ";
        }
        
        $sql .= ' WHERE TRUE ';
        
        $name = trim(Yii::$app->request->getQueryParam('place'));
        if(!empty($name)){
            $sql .= " AND LOWER(place.name) LIKE LOWER('%$name%') ";
        }
        
        $location = trim(Yii::$app->request->getQueryParam('location'));
        if(!empty($location)){
            $sql .= " AND LOWER(place.location) LIKE LOWER('%$location%') ";
        }
        
        // search nearest places /////////////
        $latitude = (float)Yii::$app->request->getQueryParam('latitude');
        $longtitude = (float)Yii::$app->request->getQueryParam('longtitude');
        $radius = (float)Yii::$app->request->getQueryParam('radius');
        
        if(!empty($latitude) && !empty($longtitude) && !empty($radius)){
            $sql .= " HAVING ( 6371 * acos( cos( radians($latitude) ) * cos( radians( latitude ) ) * cos( radians( longtitude ) - radians($longtitude) ) + sin( radians($latitude) ) * sin( radians( latitude ) ) ) ) < $radius";
        }
        ////////////////////
        
        $query = $model::findBySql($sql);
        
        $provider = new ActiveDataProvider([
            //'query' => $model->find()->joinWith('activities')->where([])->andWhere(['place.name' => 'rrrr'])->andWhere(['']),
            'query' => $query,
        ]);
        
        return $provider;
    }
    
}

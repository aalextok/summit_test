<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

use yii\web\HttpException;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

use backend\models\Place;
use backend\models\Visit;
use common\models\User;
use backend\models\Activity;
use backend\models\Participation;

class VisitController extends ActiveController
{
    public $modelClass = 'backend\models\Visit';
    public $enableCsrfValidation = false;
    
    public function actions(){
        $actions = parent::actions();
        
        unset($actions['create']);
        
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
    
    public function actionCreate(){
        $model = new $this->modelClass;
        
        $user = Yii::$app->user->identity;
        
        $model->user_id = $user->id;
        
        $placeCode = Yii::$app->getRequest()->getBodyParam('place_code');
        $place = Place::find()->where(['code' => $placeCode])->one();
        if(empty($place)){
            throw new HttpException(404, "Entity with code '$placeCode' not found.");
        }
        $model->place_id = $place->id;
        
        $activityId = Yii::$app->getRequest()->getBodyParam('activity_id');
        $activities = $place->activities;
        if(!empty($activityId)){
            $activity = Activity::find()->where(['id' => $activityId])->one();
            if(empty($activity)){
                throw new HttpException(400, "Activity #$activityId does not exist.");
            }
            
            if(empty(array_filter($activities, function($a) use($activity) {
                return $a->id == $activity->id;
            }))){
                throw new HttpException(400, "Activity #$activityId not available for place '$placeCode'"); 
            }
        }
        else{
            if(count($activities) > 1){
                throw new HttpException(400, "Please, set an 'activity_id' parameter.");
            }
            $activityId = $activities[0]->id;
        }
        
        $model->activity_id = $activityId;
        
        $visitTime = Yii::$app->getRequest()->getBodyParam('visit_time');
        if(!empty($visitTime) && (string)(int)$visitTime !== $visitTime){
            throw new HttpException(400, "Incorrect visit time value '$visitTime'");
        }
        $model->visit_time = empty($visitTime) ? time() : (int)$visitTime;
        
        
        $lastVisit = Visit::find()->where([
            'user_id' => $user->id,
            'place_id' => $place->id,
        ])->orderBy('visit_time DESC')->one();
        
        if(!empty($lastVisit) && strtotime('+ '.Yii::$app->params['visitInterval'].' hours', $lastVisit->visit_time) > $model->visit_time){
            return $lastVisit;
        }
        
        $activeParticipations = Participation::getActiveParticipations($user->id);
        $participation_id = Yii::$app->getRequest()->getBodyParam('participation_id');
        
        
        if(!empty($participation_id) && !empty(array_filter($activeParticipations, function($p) use($participation_id){
                                                    return $p->id == $participation_id;
                                                }))){
            $model->participation_id = $participation_id;
        }
        else{
            // Find participations, which belong to competitions, containing current place in their list
            $participationsOfPlace = [];
            foreach($activeParticipations as $participation){
                $competition = $participation->competition;
                $places = $competition->places;
                
                if(!empty(array_filter($places, function($p) use($place){
                    return $p->id == $place->id;
                }))){
                    $participationsOfPlace[] = $participation;
                }
            }
            
            if(count($participationsOfPlace) > 1){
                throw new HttpException(400, "Ambiguous competition: several competitions you participate in list current place ({$place->code}). "
                                                . "Please set 'participation_id' parameter.");
            }
            else if (count($participationsOfPlace) === 1){
                $model->participation_id = $participationsOfPlace[0]->id;
            }
            else{
                // There could be competitions without places.
                if(count($activeParticipations) > 1){
                    throw new HttpException(400, "Ambiguous competition: several competitions you participate in list current place ({$place->code}). "
                                                    . "Please set 'participation_id' parameter.");
                }
                else if(count($activeParticipations) === 1){
                    $model->participation_id = $activeParticipations[0]->id;
                }
                else{
                    $model->participation_id = null;
                }
            }
        }
        
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        
        if($model->hasErrors()){
            throw new HttpException(400, User::errorsToString($model->getErrors())); 
        }
        
        return $model;
    }
    
    public function prepareDataProvider(){
        $model = new $this->modelClass;
        
        $attributes = $model->attributes();
        
        $query = $model::find()->where([]);
        
        foreach($attributes as $attr){
            $param = Yii::$app->request->getQueryParam($attr);
            
            if($attr == 'user_id' && empty($param)){
                $param = Yii::$app->user->identity->id;
            }
            
            if(!empty($param)){
                if($attr == 'visit_time'){
                    $times =  explode(',', $param);
                    if(count($times) === 2){
                        $query->andWhere(['between', 'visit_time', $times[0], $times[1]]);
                    }
                    else if(count($times) === 1){
                        $query->andWhere([$attr => $param]);
                    }
                }
                else{
                    $query->andWhere([$attr => $param]);
                }
            }
        }
        
        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $provider;
    }
    
    public function checkAccess($action, $model = null, $params = array()) {
        if(($action == 'delete' || $action == 'update') && !empty($model) && $model->user_id !== Yii::$app->user->identity->id){
            throw new \yii\web\ForbiddenHttpException;
        }
        
        parent::checkAccess($action, $model, $params);
    }
}

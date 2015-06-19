<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;

use yii\web;

use backend\models\Participation;
use backend\models\Competition;
use common\models\User;

class ParticipationController extends ActiveController
{
    public $modelClass = 'backend\models\Participation';
    public $enableCsrfValidation = false;
    
    public function actions(){
        $actions = parent::actions();
        
        unset($actions['create']);

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
        
        $model = new Participation();
        $user = Yii::$app->user->identity;
        
        $model->user_id = $user->id;
        
        $competitionCode = Yii::$app->getRequest()->getBodyParam('competition_code');
        $competition = Competition::find()->where(['code' => $competitionCode])->one();
        if(empty($competition)){
            throw new web\HttpException(404, "Entity with code '$competitionCode' not found.");
        }
        
        if(isset($competition->close_time) && $competition->close_time < time()){
            throw new web\HttpException(400, "Competition is closed at ".date('Y-m-d', $competition->close_time).".");
        }
        
        $model->start_time = time();
        
        $model->competition_id = $competition->id;
        
        $duplicates = Participation::find()->where([
            'user_id' => $model->user_id,
        ])->andWhere([
            'competition_id' => $model->competition_id,
//        ])->andWhere([
//            'finish_time' => null,
        ])->all();
        
        if(count($duplicates) > 0){
            throw new web\HttpException(400, "User already participates in this competition."); 
        }

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        
        if($model->hasErrors()){
            throw new web\HttpException(400, User::errorsToString($model->getErrors())); 
        }
        
        return $model;
    }
    
}

<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\helpers\Url;

class ImageController extends ActiveController
{
    public $modelClass = 'backend\models\Image';
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
        $user = Yii::$app->user->identity;
        
        if(Yii::$app->request->isPost){
            
            $image = new $this->modelClass;
            
            $image->image = UploadedFile::getInstance($image, 'image');

            if(empty($image->image)){
                throw new HttpException(400, 'File not uploaded.'); 
            }
            
            $image->model = ucfirst(Yii::$app->request->post('model'));
            $image->model_id = Yii::$app->request->post('model_id');
            
            if(!in_array($image->model, ['User', 'Visit', 'Participation'])){
                throw new HttpException(400, "Wrong 'model' parameter. Only: 'User', 'Visit' and 'Participation' are available."); 
            }
            
            $name = Yii::$app->request->post('name');
            $image->name = empty($name) ? pathinfo($image->image->name, PATHINFO_FILENAME) : $name;
            $image->description = Yii::$app->request->post('description');
            $image->hash = hash_file('md5', $image->image->tempName);
            
            $namespace = $image->model == 'User' ? 'common\models\\' : 'backend\models\\';
            $className = $namespace.$image->model;
            $target = $className::find()->where(['id' => $image->model_id])->one();
            
            if(empty($target)){
                throw new HttpException(404, "Object {$image->model}#{$image->model_id} not found.");
            }
            
            if((in_array($image->model, ['Visit', 'Participation']) && $target->user_id != $user->id) || ($image->model == 'User' && $target->id != $user->id)){
                throw new HttpException(403, "Model does not belong to current user.");
            }
            
            $path = Yii::$app->params['imgDir'].strtolower($image->model).'/'.$image->model_id;
            
            $image->image->name = $path.'/'.uniqid().'.'.$image->image->extension;
            $image->location = $image->image->name;
            
            $isAvatar = filter_var(Yii::$app->request->post('is_avatar'), FILTER_VALIDATE_BOOLEAN);
            if($isAvatar && $image->model == 'User'){
                $user->image = $image->location;
                $user->image_hash = $image->hash;
                
                if(!$user->save()){
                    throw new ServerErrorHttpException("Unable to set avatar for a User#{$user->id}.");
                }
            }
            
            if($image->save()){   
                
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }

                $image->image->saveAs($image->location);

                $response = Yii::$app->getResponse();
                $response->setStatusCode(201);
                $id = implode(',', array_values($image->getPrimaryKey(true)));
                $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
            }
            elseif (!$image->hasErrors()) {
                throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
            }
            
            if($image->hasErrors()){
                throw new HttpException(400, User::errorsToString($image->getErrors())); 
            }
            
            return $image;
            
        }
        throw new HttpException(405, 'Request not of POST type');
        
    }
    
}

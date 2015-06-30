<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\helpers\Url;

use backend\models\Image;
use common\models\User;

class ImageController extends ActiveController
{
    public $modelClass = 'backend\models\Image';
    public $enableCsrfValidation = false;
    
    public function actions(){
        $actions = parent::actions();
        
        //unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

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
            
            $isAvatar = filter_var(Yii::$app->request->post('is_avatar'), FILTER_VALIDATE_BOOLEAN);
            
            if($isAvatar){
                
                $image->model = 'User';
                $image->model_id = $user->id; 
                
            }else{
                
                $image->model = ucfirst(Yii::$app->request->post('model'));
                $image->model_id = Yii::$app->request->post('model_id');
            }
            
            
            if(!in_array($image->model, $image::$masterEntities)){
                throw new HttpException(400, "Model entity '{$image->model}' does not exist or cannot be linked to Image"); 
            }
            
            $image->user_id = $user->id;
            $name = Yii::$app->request->post('name');
            $image->name = empty($name) ? pathinfo($image->image->name, PATHINFO_FILENAME) : $name;
            $image->description = Yii::$app->request->post('description');
            $image->hash = hash_file('md5', $image->image->tempName);
            
            
            $master = $image->master;
            if(empty($master)){
               throw new HttpException(404, "Object {$image->model}#{$image->model_id} not found."); 
            }
            
            if(!$image->masterBelongsTo($user)){
                throw new HttpException(403, "Model does not belong to current user.");
            }
            
            $path = Yii::$app->params['imgDir'].strtolower($image->model).'/'.$image->model_id;
            
            $image->image->name = $path.'/'.uniqid().'.'.$image->image->extension;
            $image->location = $image->image->name;
            
            
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
    
    public function actionUpdate($id){
        
        $image = Image::find()->where(['id' => $id])->one();
        
        if(empty($image)){
            throw new HttpException(404, "Image #$id not found.");
        }
        
        if($image->user_id != Yii::$app->user->identity->id){
            throw new HttpException(403, "Cannot edit Image belonging to another user.");
        }
        
        $image->scenario = 'update';
        
//        $master = $image->master;
//        
//        if(empty($master)){
//            throw new HttpException(404, "Object {$image->model}#{$image->model_id} not found.");
//        }
//        if(!$image->masterBelongsTo(Yii::$app->user->identity)){
//            throw new HttpException(403, "Model does not belong to current user.");
//        }
        
        $image->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($image->save() === false && !$image->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        
        if($image->hasErrors()){
            throw new HttpException(400, User::errorsToString($image->getErrors())); 
        }

        return $image;
    }
    
    public function actionDelete($id){
        $image = Image::find()->where(['id' => $id])->one();
        $user = Yii::$app->user->identity;
        
        if(empty($image)){
            throw new HttpException(404, "Image #$id not found.");
        }

        if ($image->user_id != $user->id){
            throw new HttpException(403, "Cannot delete Image belonging to another user.");
        }
        
        $path = $image->location;

        if ($image->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }
        
        try{
            $unlinked = unlink($path);
        }
        catch (yii\base\ErrorException $e){}
        
        if(!$unlinked){
            throw new ServerErrorHttpException('Failed to image file for unknown reason.');
        }
        
        if($user->image == $path){
            $user->image = null;
            $user->image_hash = null;
            
            $user->save();
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    public function checkAccess($action, $model = null, $params = array()) {
        if($action == 'index'){
            throw new \yii\web\ForbiddenHttpException;
        }
        
        parent::checkAccess($action, $model, $params);
    }
}

<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use common\models\User;
use backend\models\ResetForm;

use backend\models\Image;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'reset'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'index', 
                            'docs', 
                            'delete-image', 
                            'upload-images'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action){
                            return Yii::$app->user->identity->role === User::ROLE_ADMIN;
                        }
                    ],
                ],
                'denyCallback' => function($rule, $action){
                    if(\Yii::$app->user->isGuest || Yii::$app->user->identity->role !== User::ROLE_ADMIN)
                        // TODO if not admin, redirect to frontend
                        return $action->controller->redirect(Yii::$app->urlManager->createUrl("site/login"));
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        //return $this->render('index');
        return $this->redirect(Yii::$app->urlManager->createUrl("user-dashboard/index"));
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->load(Yii::$app->request->post());
        $user = User::findByUsername($model->username);
        //TODO create two user login forms in frontend and backend
        if(isset($user)){
            if($user->role !== User::ROLE_ADMIN){
                throw new \yii\web\HttpException(401);
            }
        }
        
        if ($model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
//    public function actionReset(){
//        $reset_token = Yii::$app->request->get('reset_token');
//        if(isset($reset_token)){           
//            $resetForm = new ResetForm();
//            $resetForm->token = $reset_token;
//        }
//        else{
//            $resetForm = new ResetForm();
//            $resetForm->load(Yii::$app->request->post());
//            if($resetForm->validate()){
//                $user = User::findByPasswordResetToken($resetForm->token);
//                if(!empty($user) && $user->isPasswordResetTokenValid($resetForm->token)){
//                    $user->password = $resetForm->password;
//                    $resetForm->reset_successfully = $user->save();
//                }
//            }
//        }
//        return $this->render('reset', [
//                'model' => $resetForm,
//            ]);
//    }
//    
//    public function actionDocs(){
//        return $this->render('docs');
//    }
    
    public function actionUploadImages(){  
        $model = Yii::$app->request->get('model');
        $modelId = Yii::$app->request->get('model_id');
        $classname = Yii::$app->request->get('classname');
        $replaceId = Yii::$app->request->get('replace_id');
        
        if(empty($model) || empty($modelId) || empty($classname)){
            return json_encode([
                'error' => 'Model of model ID not defined.'
            ]);
        }
        
        $target = $classname::find()->where(['id' => $modelId])->one();
        
        if(empty($target)){
            return json_encode([
                'error' => 'Object to attach file to not found.',
            ]);
        }
        
        $image = new Image();
        $image->image = UploadedFile::getInstanceByName('images');
        
        if(empty($image->image)){
            return json_encode([
                'error' => 'File not uploaded.'
            ]);
        }
        
        $image->model = $model;
        $image->model_id = $modelId;
        
        $image->name = pathinfo($image->image->name, PATHINFO_FILENAME);
        $image->hash = hash_file('md5', $image->image->tempName);
        
        $path = Yii::$app->params['imgDir'].strtolower($image->model).'/'.$image->model_id;
        $image->image->name = $path.'/'.uniqid().'.'.$image->image->extension;
        $image->location = $image->image->name;
        
        if(!$image->save()){
            return json_encode([
                'error' => 'Cannot save file.'
            ]);
        }
        
        if(!is_dir($path)){
            mkdir($path, 0777, true);
        }

        $image->image->saveAs($image->location);
        
        if(!empty($replaceId)){
            $replace = Image::find()->where(['id' => $replaceId])->one();
            
            if(!empty($replace)){
                try{
                    unlink($replace->location);
                    $replace->delete();
                }
                catch(yii\base\ErrorException $e){}
            }
        }
        
        $initialPreview = [];
        $initialPreviewConfig = [];
        
        $initialPreview[] = Html::img(Yii::getAlias('@web').'/'.$image->location, [
            'class'=>'file-preview-image', 
            'alt' => $image->name, 
            'title' => $image->name
        ]);
        
        $initialPreviewConfig[] = [
            'caption' => $image->name,
            'url' => Url::to(['site/delete-image', 'id' => $image->id]),
            'key' => $image->id,
        ];
        
        return json_encode([
            'ok' => 'Image successfully uploaded.',
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig,
            'append' => ($replaceId) ? false : true,
        ]);
    }


    public function actionDeleteImage($id){
        $image = Image::find()->where(['id' => $id])->one();
        
        if(empty($image)){
            return json_encode([
                'error' => "Image #{$id} not found.",
            ]);
        }
        
        $unlinked = false;
        $deleted = false;
        
        try{
            $unlinked = unlink($image->location);
        }
        catch (yii\base\ErrorException $e){}
        
        $deleted = $image->delete();
        
        if(!$unlinked || !$deleted){
            return json_encode([
                'error' => "Image #{$id} cannot be removed.",
            ]);
        }
        
        return json_encode([
            'ok' => "Image #{$id} removed.",
        ]);
    }
}

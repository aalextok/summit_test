<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use common\models\User;
use backend\models\ResetForm;

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
                        'actions' => ['index', 'docs'],
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
}

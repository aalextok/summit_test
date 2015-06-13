<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\authclient\clients\Facebook;

use yii\helpers\Url;
use backend\models\Activity;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends \frontend\controllers\BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
              'class' => 'yii\authclient\AuthAction',
              'successCallback' => [
                $this, 'successCallback'
              ]
            ],
        ];
    }

    public function actionIndex()
    {
        
        if (!Yii::$app->user->isGuest) {
          return $this->redirect( Url::toRoute("site/dashboard") , 301);
        }
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionDashboard()
    {
        $allActivities = Activity::find()->all();
        
        return $this->render('dashboard', array(
            "allActivities" => $allActivities
        ));
    }
    
    public function successCallback($client)
    {
      $attributes = $client->getUserAttributes();
      
      $check = User::findIdentityByEmail($attributes['email'], false);
      
      if($check){
        //login
        die('Login missing');
      } else {
        //create and login
        
        $oauthToken = $client->getAccessToken();
        
        //Yii::$app->components['authClientCollection']['clients']['facebook']['clientId']
        $facebook = new Facebook();
        
        $facebook->setAccessToken( $oauthToken );
        $userAttributes = $facebook->getUserAttributes();
        //$tokenAttr = $facebook->api('debug_token', 'GET', ['input_token' => $token, 'access_token' => $token]);
        $picture = $facebook->api('me/picture', 'GET', ['redirect' => 'false']);
        
        pre( $picture['data']['url'] );
        die;
      }
      
      /*
(
    [id] => 10207456699999429
    [email] => janar@eagerfish.eu
    [first_name] => Janar
    [gender] => male
    [last_name] => Jurisson
    [link] => https://www.facebook.com/app_scoped_user_id/10207456699999429/
    [locale] => en_US
    [name] => Janar Jurisson
    [timezone] => 3
    [updated_time] => 2014-12-22T06:04:20+0000
    [verified] => 1
)*/
    }
    
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
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

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}

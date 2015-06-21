<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'=>[
            'class'=>'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=summittosea',
            'username' => 'summittosea',
            'password' => 'MtEvhxzBULmhuAFw',
            'charset' => 'utf8'            
        ],
        'assetManager' => [
          'bundles' => [
          ],
        ],
        
        
        'urlManager' => [
          'enablePrettyUrl' => true,
          'showScriptName' => false,
          'enableStrictParsing' => false,
          'rules' => [
            'dashboard' => 'site/dashboard',
            'settings' => 'profile/settings',
            'about' => 'site/about',
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            '<controller:\w+>/' => '<controller>/index',
            'module/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
          ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authClientCollection' => [
          'class' => 'yii\authclient\Collection',
          'clients' => [
            'facebook' => [
              'class' => 'yii\authclient\clients\Facebook',
              'clientId' => '1600686150186769',
              'clientSecret' => '94f0db5dca9e94deafc205e60c5b0fe5',
            ],
          ],
        ]
    ],
    'params' => $params,
];

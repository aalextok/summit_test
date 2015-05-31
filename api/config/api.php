<?php

$db     = require(__DIR__ . '/../../config/db.php');
$params = require(__DIR__ . '/params.php');

$config = [
	'id' => 'app-api',
	// Need to get one level up:
	'basePath' => dirname(__DIR__).'/..',
	'bootstrap' => ['log'],
	'components' => [
		'request' => [
			// Enable JSON Input:
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
					// Create API log in the standard log dir
					// But in file 'api.log':
					'logFile' => '@app/runtime/logs/api.log',
				],
			],
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'enableStrictParsing' => true,
			'showScriptName' => false,
			'rules' => [
				/* Cards */
				'GET cards' => 'v1/cards/list',
				'GET cards/<hash:\w+>' => 'v1/cards/details',
				'POST cards/<hash:\w+>' => 'v1/cards/save',
				/* Customer */
				'GET customer' => 'v1/customer/get',
				'POST customer' => 'v1/customer/register',
				'POST customer/register_device' => 'v1/customer/device',
			],
		], 
		'db' => $db,
	],
	'modules' => [
		'v1' => [
			'class' => 'app\api\modules\v1\Module',
		],
	],
	'params' => $params,
];

return $config;

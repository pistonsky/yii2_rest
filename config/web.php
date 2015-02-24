<?php

require(__DIR__ . '/constants.php'); // файл с кодами ошибок и другими константами

$params = require(__DIR__ . '/params.php');

$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'components' => [
		'urlManager' => [
			'enablePrettyUrl' => true,
			'enableStrictParsing' => true,
			'showScriptName' => false,
			'rules' => [
				'GET register' => 'register',
				'GET questions' => 'question',
				'GET messages' => 'message',
				'POST question' => 'question/add',
				'POST message' => 'message/add',
				'DELETE question/<id:\d+>' => 'question/delete',
				'DELETE message/<id:\d+>' => 'message/delete',
			],
		],
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => '12b62091ahjk',
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
		],
		'user' => [
			'class' => 'app\components\User',
			'identityClass' => 'app\models\Users',
			'enableAutoLogin' => false,
			'enableSession' => false,
			'loginUrl' => null
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true,
		],
		'log' => [
			// 'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['info'],
					'categories' => ['api', 'application'],
					'logFile' => "@runtime/logs/api.log",
					'maxFileSize' => 128,
					'logVars' => ['_POST'],
				],
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['profile'],
					'categories' => ['/init'], // just as an example
					'logFile' => "@runtime/logs/init.log",
					'maxFileSize' => 128,
					'logVars' => [],
				],
				[
					'class' => 'app\components\FileTargetShort',
					'levels' => ['info'],
					'categories' => ['/init'], // just as an example
					'logFile' => "@runtime/logs/init-totals.log",
					'maxFileSize' => 128,
					'logVars' => [],
					'prefix' => function ($message) {
						return "";
					},
				]
			],
		],
		'db' => require(__DIR__ . '/db.php')
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = 'yii\debug\Module';

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		'allowedIPs' => ['127.0.0.1']
	];

	$config['bootstrap'][] = 'log';
}

return $config;

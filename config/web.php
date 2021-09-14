<?php

$params = require __DIR__ . '/params.php';

$config = [
	'id' => 'basic',
	'timeZone' => 'Europe/Berlin',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	// 'language'=>'de',
	'aliases' => [
		// Set the editor language dir
		'@media' => '@app/uploads/media',
	],
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'cookieValidationKey',
		],

		'getTable' => [
			'class' => 'app\components\getTable',
		],
		'notify' => [
			'class' => 'app\components\notify',
		],
		'Utility' => [
			'class' => 'app\components\Utility',
		],

		'cache' => [
			'class' => 'yii\caching\FileCache',
		],

		'user' => [
			'identityClass' => 'app\models\User',
			//'enableAutoLogin' => false,
			//'enableSession' => false,
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
			// 'defaultRoles' => ['guest'],
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		// 'session' => [
		// 	'class' => 'yii\web\DbSession',
		// 	'writeCallback' => function ($session) {
		// 		return [
		// 			'user_id' => Yii::$app->user->id,
		// 		];
		// 	},
		// 	// 'db' => 'mydb',
		// 	// 'sessionTable' => 'my_session',
		// ],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			// 'useFileTransport' => false,
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'smtp.ionos.de',
				'username' => 'crm@mf-1.de',
				'password' => 'D&=HoE*r&Jr8As',
				'port' => '587',
				'encryption' => 'tls',
			],
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
		'db' => require __DIR__ . '/db.php',

		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
		],

		'i18n' => [
			'translations' => [
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					// 'sourceLanguage' => 'de',
					'fileMap' => [
						'app' => 'app.php',
						'app/error' => 'error.php',
					],
				],
			],
		],

	],
	'as beforeRequest' => [
		'class' => 'app\components\changeLanguage',
	],

	'params' => $params,

	'modules' => [
		'admin' => [
			'class' => 'app\modules\admin\Admin',

			'as access' => [
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
					],
				],
			],
		],
		'chat' => [
			'class' => 'app\modules\chat\Chat',
			'as access' => [
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		],
		'mailbox' => [
			'class' => 'app\modules\mailbox\Mailbox',
			'as access' => [
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		],
		'report' => [
			'class' => 'app\modules\report\Report',
			'as access' => [
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		],
	],
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	// $config['bootstrap'][] = 'debug';
	// $config['modules']['debug'] = [
	// 	'class' => 'yii\debug\Module',
	// 	// uncomment the following to add your IP if you are not connecting from localhost.
	// 	//'allowedIPs' => ['127.0.0.1', '::1'],
	// ];
	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		'allowedIPs' => ['127.0.0.1', '::1', '192.168.18.*'],
	];
}

return $config;

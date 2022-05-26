<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '-tF52Mzu0T4gS9om0jQdyMD81POJZoyz',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
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
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'profile/<username>' => 'profile/index',
                '<controller:(auth)>/<action:(login|logout)>' => '<controller>/<action>',
                'admin/<controller:(users|groups|disciplines)>' => '<controller>/index',
                'admin/<controller:(users|groups|disciplines)>/<action:(create|view|update)>/<id:\d+>' => '<controller>/<action>',
                'admin/<controller:(users)>/<action:(block|change-password)>' => '<controller>/<action>',
                'admin/<controller:(groups)>/<action:(add-student|remove-student)>' => '<controller>/<action>',
                'admin/<controller:(disciplines)>/<action:(add-teacher|remove-teacher)>' => '<controller>/<action>',
                'teacher/<controller:(estimates|certification)>' => '<controller>/index',
                'teacher/<controller:(estimates)>/<action:(view)>/<id:\d+>' => '<controller>/<action>',
                'teacher/certification/<group>/<discipline>' => 'certification/check-certification',
                'teacher/certification/fill/<group>/<discipline>' => 'certification/fill-certification',
                '<controller:(estimates)/<action:(remove-mark|remove-marks|remove-marks-by-month)>/<id:\d+>' => '<controller>/<action>',
                '<controller:(estimates)/<action:(remove-group-marks)/<id:\d+>/<discipline:\d+>' => '<controller>/<action>',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

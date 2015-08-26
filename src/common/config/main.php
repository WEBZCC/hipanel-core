<?php

function d ($a) { print "<pre>"; var_dump($a); debug_print_backtrace(0,3); die(); }

$params = array_merge(
    require(Yii::getAlias('@hipanel/common/config/params.php')),
    require(Yii::getAlias('@project/common/config/params.php')),
    require(Yii::getAlias('@project/common/config/params-local.php'))
);

$config = [
    'vendorPath' => '@project/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'request' => [
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'authManager' => [
            'class' => 'hipanel\base\AuthManager',
        ],
        'hiresource' => [
            'class'  => 'hipanel\base\Connection',
            'config' => [
                'api_url' => $params['api_url'],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];

if (YII_DEBUG && !YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => $params['debug_ips'],
        'panels' => [
            'hiresource' => [
                'class' => 'hiqdev\hiart\DebugPanel',
            ]
        ]
    ];


    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
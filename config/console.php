<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id'                  => 'basic-console',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases'             => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components'          => [
        'cache'  => [
            'class' => 'yii\caching\FileCache',
        ],
        'log'    => [
            'targets' => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['info', 'error', 'warning'],
                    'logVars' => [],
                    'logFile' => '@runtime/logs/commands.log'
                ]
            ],
        ],
        'db'     => $db,
        'tender' => [
            'class'  => \app\components\TenderComponent::class,
            'config' => [
                'clientClass'  => GuzzleHttp\Client::class,
                'baseUri'      => 'https://public.api.openprocurement.org/api/0/',
                'limitPerPage' => 50,
            ]
        ],
    ],
    'params'              => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV){
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

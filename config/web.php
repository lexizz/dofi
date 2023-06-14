<?php

use app\components\errorHandlers\ApiErrorHandler;
use app\repositories\file\FileRepository;
use app\repositories\file\FileRepositoryInterface;
use app\repositories\statistic\StatisticRepository;
use app\repositories\statistic\StatisticRepositoryInterface;
use app\services\file\saveFile\SaveFileService;
use app\services\file\saveFile\SaveFileServiceInterface;
use app\services\file\sendFile\SendFileService;
use app\services\file\sendFile\SendFileServiceInterface;
use app\services\statistic\getPopulateContent\GettingPopulateContentService;
use app\services\statistic\getPopulateContent\GettingPopulateContentServiceInterface;
use app\services\statistic\getTopIP\GettingTopIPService;
use app\services\statistic\getTopIP\GettingTopIPServiceInterface;
use yii\caching\CacheInterface;
use yii\di\Instance;
use yii\log\Logger;
use yii\redis\Cache;
use yii\symfonymailer\Mailer;
use yii\web\Request;
use yii\web\Response;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'dofi',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '6x9QqBcq-rD5L_aEf7KotweEAqGSZrkA',
        ],
        'response' => 'response',
        'cache' => CacheInterface::class,
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'class' => ApiErrorHandler::class,
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'api/<modules>/<controller>/<action>/<filename:[.\w-]+>' => '<modules>/<controller>/<action>',
                'api/<modules>/<controller>/<action>' => '<modules>/<controller>/<action>',
                'GET /' => 'site/index',
            ],
        ],
    ],
    'container' => [
        'singletons' => [
            'cache' => [
                'class' => Cache::class,
                'keyPrefix' => getenv('REDIS_PREFIX'),
                'defaultDuration' => (int)getenv('REDIS_DEFAULT_DURATION_CACHE'),
                'redis' => [
                    'hostname' => getenv('REDIS_HOST'),
                    'port' => getenv('REDIS_PORT'),
                    'database' => getenv('REDIS_DATABASE'),
                ],
            ],
            CacheInterface::class => 'cache',
            'response' => [
                'class' => Response::class,
                'format' => Response::FORMAT_JSON,
            ],
            StatisticRepositoryInterface::class => StatisticRepository::class,
            FileRepositoryInterface::class => FileRepository::class,
            GettingTopIPServiceInterface::class => GettingTopIPService::class,
            GettingPopulateContentServiceInterface::class => GettingPopulateContentService::class,
            SaveFileServiceInterface::class => [
                ['class' => SaveFileService::class],
                [
                    $params['fileUploadParams'],
                    Instance::of(FileRepositoryInterface::class),
                    Instance::of(CacheInterface::class),
                ]
            ],
            SendFileServiceInterface::class => [
                ['class' => SendFileService::class],
                [
                    Instance::of(Request::class),
                    Instance::of('response'),
                    Instance::of(StatisticRepositoryInterface::class),
                    Instance::of(FileRepositoryInterface::class),
                    Instance::of(CacheInterface::class),
                    Instance::of(Logger::class),
                ]
            ]
        ],
    ],
    'params' => $params,
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => YII_ENV_DEV ? ['*'] : [],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => YII_ENV_DEV ? ['*'] : [],
    ];
}

return $config;

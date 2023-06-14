<?php

if (file_exists(__DIR__ . '/../.env')) {
    $repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
        ->addAdapter(Dotenv\Repository\Adapter\EnvConstAdapter::class)
        ->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)
        ->make();

    Dotenv\Dotenv::create($repository, __DIR__ . '/..')->load();
}

defined('YII_DEBUG') || define('YII_DEBUG', getenv('YII_DEBUG') ?: false);
defined('YII_ENV') || define('YII_ENV', getenv('YII_ENV') ?: 'prod');

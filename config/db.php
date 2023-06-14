<?php

use yii\db\Connection;

$host = getenv('SQL_HOST');
$db = getenv('SQL_DATABASE');
$user = getenv('SQL_USERNAME');
$pass = getenv('SQL_PASSWORD');

return [
    'class' => Connection::class,
    'dsn' => "mysql:host={$host};dbname={$db}",
    'username' => $user,
    'password' => $pass,
    'charset' => 'utf8',
//    'enableSchemaCache' => true,
//    'schemaCacheDuration' => 3 * 60 * 60 * 24,
//    'schemaCache' => 'cache',
];

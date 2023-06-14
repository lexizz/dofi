<?php

namespace app\services\file\sendFile;

use yii\web\Response;

interface SendFileServiceInterface
{
    public function handle(string $filename): Response;
}
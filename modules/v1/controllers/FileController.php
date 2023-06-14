<?php

namespace app\modules\v1\controllers;

use app\components\EndpointRateLimiter;
use app\services\file\saveFile\SaveFileServiceInterface;
use app\services\file\sendFile\SendFileServiceInterface;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Request;
use yii\web\Response;

class FileController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'download' => ['get'],
                    'upload' => ['post'],
                ],
            ],
            'rateLimiter' => [
                'class' => EndpointRateLimiter::class,
                'enableRateLimitHeaders' => true,
            ],
        ];
    }

    /**
     * @param string $filename
     * @param SendFileServiceInterface $sendFileService
     * @return Response
     */
    public function actionDownload(
        string $filename,
        SendFileServiceInterface $sendFileService,
    ): Response
    {
        return $sendFileService->handle($filename);
    }

    public function actionUpload(
        Request $request,
        Response $response,
        SaveFileServiceInterface $saveFileService
    ): array
    {
        if (!$request->isPost) {
            $response->statusCode = 405;
            return ['message' => 'Method not allowed!'];
        }

        $directoryForSave = Yii::getAlias('@runtime/files/');

        $saveFileService->handle($directoryForSave);

        return ['message' => 'File uploaded successfully!'];
    }
}

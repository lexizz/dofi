<?php

namespace app\modules\v1\controllers;

use app\components\EndpointRateLimiter;
use app\services\statistic\getPopulateContent\GettingPopulateContentServiceInterface;
use app\services\statistic\getTopIP\GettingTopIPServiceInterface;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class StatisticController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'populateContent' => ['get'],
                    'topIp' => ['get'],
                ],
            ],
            'rateLimiter' => [
                'class' => EndpointRateLimiter::class,
                'enableRateLimitHeaders' => true,
            ],
        ];
    }

    public function actionPopulateContent(GettingPopulateContentServiceInterface $populateContentService): array
    {
        return $populateContentService->handle();
    }

    public function actionTopIp(GettingTopIPServiceInterface $topIPService): array
    {
        return $topIPService->handle();
    }
}

<?php

namespace app\components;

use Yii;
use yii\base\Action;
use yii\filters\RateLimiter;
use yii\filters\RateLimitInterface;
use yii\redis\Cache;
use yii\web\Request;
use yii\web\TooManyRequestsHttpException;

class EndpointRateLimiter extends RateLimiter implements RateLimitInterface
{
    /**
     * @throws TooManyRequestsHttpException
     */
    public function beforeAction($action): bool
    {
        $this->checkRateLimit($this, $this->request, $this->response, $action);

        return true;
    }

    /**
     * Returns the maximum number of allowed requests and the window size.
     *
     * @param Request $request the current request
     * @param Action $action the action to be executed
     *
     * @return array an array of two elements. The first element is the maximum number of allowed requests,
     *               and the second element is the size of the window in seconds.
     *
     */
    public function getRateLimit($request, $action): array
    {
        $methodName = $this->getMethodName($request);
        $rateLimiterParams = Yii::$app->params['rateLimiter'];

        if ('' !== $methodName) {
            $methodLimit = $rateLimiterParams['methodsParams'][$methodName] ?? null;

            if (null !== $methodLimit) {
                return [$methodLimit['requests'], $methodLimit['perSeconds']];
            }
        }

        return [
            $rateLimiterParams['defaultMethodParams']['requests'],
            $rateLimiterParams['defaultMethodParams']['perSeconds'],
        ];
    }

    /**
     * Loads the number of allowed requests and the corresponding timestamp from a persistent storage.
     *
     * @param Request $request the current request
     * @param Action $action the action to be executed
     *
     * @return array an array of two elements. The first element is the number of allowed requests,
     *               and the second element is the corresponding UNIX timestamp.
     *
     */
    public function loadAllowance($request, $action): array
    {
        /** @var Cache $cache */
        $cache = Yii::$app->cache;
        $value = $cache->get($this->getMethodName($request));

        if (false === $value) {
            [$limit] = $this->getRateLimit($request, $action);

            return [$limit, time()];
        }

        $value = json_decode($value);

        return [$value->allowance, $value->timestamp];
    }

    /**
     * Saves the number of allowed requests and the corresponding timestamp to a persistent storage.
     *
     * @param Request $request the current request
     * @param Action $action the action to be executed
     * @param int $allowance the number of allowed requests remaining
     * @param int $timestamp the current timestamp
     *
     */
    public function saveAllowance($request, $action, $allowance, $timestamp): void
    {
        $data = json_encode(['allowance' => $allowance, 'timestamp' => $timestamp]);

        /** @var Cache $cache */
        $cache = Yii::$app->cache;
        $cache->set($this->getMethodName($request), $data, Yii::$app->params['rateLimiter']['defaultCacheDuration']);
    }

    private function getMethodName(Request $request): string
    {
        return strtolower($request->getMethod())
            . '.' . Yii::$app->controller->id
            . '.' . Yii::$app->controller->action->id;
    }
}

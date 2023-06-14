<?php

namespace app\components\errorHandlers;

use yii\base\UserException;
use yii\db\Exception;
use yii\web\ErrorHandler;

class ApiErrorHandler extends ErrorHandler
{
    protected function convertExceptionToArray($exception)
    {
        $array = [
            'message' => $exception->getMessage(),
        ];

        if (YII_DEBUG) {
            $array['type'] = get_class($exception);
            if (!$exception instanceof UserException) {
                $array['file'] = $exception->getFile();
                $array['line'] = $exception->getLine();
                $array['stack-trace'] = explode("\n", $exception->getTraceAsString());
                if ($exception instanceof Exception) {
                    $array['error-info'] = $exception->errorInfo;
                }
            }
            if (($prev = $exception->getPrevious()) !== null) {
                $array['previous'] = $this->convertExceptionToArray($prev);
            }
        }

        return $array;
    }
}

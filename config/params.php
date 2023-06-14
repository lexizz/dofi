<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'fileUploadParams' => [
        'allowedExtension' => getenv('FILE_ALLOWED_EXTENSION')
            ? explode(';', trim(getenv('FILE_ALLOWED_EXTENSION'), ';'))
            : null,
        'maxSize' => getenv('FILE_MAX_SIZE')
            ? ((int)(getenv('FILE_MAX_SIZE') ?: 0) * 1024 * 1024)
            : null,
    ],
    'rateLimiter' => [
        'enable' => true,
        'defaultCacheDuration' =>  60,
        'defaultMethodParams' => [
            'requests' => 30,
            'perSeconds' => 60,
        ],
        'methodsParams' => [ // name of method set - "{http method in lower case}.{controllerID}.{actionID}"
            'get.statistic.top-ip' => null,
            'get.statistic.populate-content' => null,
            'post.file.upload' => null,
            'get.file.download' => [
                'requests' => 5,
                'perSeconds' => 10,
            ],
        ],
    ],
];

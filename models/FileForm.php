<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class FileForm extends Model
{
    private array $allowedExtensions;
    private int $maxSize;

    public function __construct(array $allowedExtensions, int $maxSize, $config = [])
    {
        parent::__construct($config);

        $this->allowedExtensions = $allowedExtensions;
        $this->maxSize = $maxSize;
    }

    public ?UploadedFile $file;

    public function rules(): array
    {
        return [
            [['file'], 'file',
                'skipOnEmpty' => false,
                'checkExtensionByMimeType' => false,
                'extensions' => $this->allowedExtensions,
                'maxSize' => $this->maxSize
            ],
        ];
    }
}
<?php

namespace app\services\file\saveFile;

use app\models\FileForm;
use app\repositories\file\FileRepositoryInterface;
use app\services\file\dto\FileDTO;
use DateTimeImmutable;
use RuntimeException;
use yii\redis\Cache;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class SaveFileService implements SaveFileServiceInterface
{
    private array $uploadParams = [];
    private FileRepositoryInterface $fileRepository;
    private Cache $cache;

    private array $listErrors = [
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    ];

    public function __construct(array $uploadParams, FileRepositoryInterface $fileRepository, Cache $cache)
    {
        $this->uploadParams = $uploadParams;
        $this->fileRepository = $fileRepository;
        $this->cache = $cache;
    }

    /**
     * @throws BadRequestHttpException
     */
    public function handle(string $directoryForSave): void
    {
        if (empty($directoryForSave)) {
            throw new BadRequestHttpException('Directory for saving file can\'t be empty');
        }

        $uploadedFile = UploadedFile::getInstanceByName('file');

        if (null === $uploadedFile) {
            throw new BadRequestHttpException('Param \'file\' not found');
        }

        $pathForSave = $directoryForSave . $uploadedFile->name;

        if (file_exists($pathForSave)) {
            throw new BadRequestHttpException('File \'' . $uploadedFile->name . '\' has already exists');
        }
;
        $fileForm = new FileForm($this->uploadParams['allowedExtension'], $this->uploadParams['maxSize']);

        $fileForm->file = $uploadedFile;

        if (!$fileForm->validate()) {
            throw new BadRequestHttpException(implode(';', $fileForm->getErrorSummary(true)));
        }

        $resultSave = $uploadedFile->saveAs($pathForSave);

        if (!$resultSave) {
            throw new BadRequestHttpException($this->listErrors[$uploadedFile->error]);
        }

        $fileStat = stat($pathForSave);

        $fileDTO = new FileDTO();
        $fileDTO->setFilename($uploadedFile->name);
        $fileDTO->setExtension($uploadedFile->extension ?: null);
        $fileDTO->setPathToFile($pathForSave);
        $fileDTO->setSize($uploadedFile->size);
        $fileDTO->setMime($uploadedFile->type);
        $fileDTO->setMtime((new DateTimeImmutable)->setTimestamp($fileStat['mtime']));
        $fileDTO->setCtime((new DateTimeImmutable)->setTimestamp($fileStat['ctime']));

        try {
            $this->fileRepository->save($fileDTO);
        } catch (RuntimeException $exception) {
            unlink($pathForSave);
            throw new $exception;
        }

        $this->cache->set($uploadedFile->name, $fileDTO);
    }
}
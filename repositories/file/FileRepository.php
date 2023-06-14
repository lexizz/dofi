<?php

namespace app\repositories\file;

use app\models\File;
use app\services\file\dto\FileDTO;
use app\services\file\dto\FileListDTO;
use DateTimeImmutable;
use DomainException;
use Exception;
use RuntimeException;
use Throwable;
use yii\web\NotFoundHttpException;

class FileRepository implements FileRepositoryInterface
{
    public function exist(int $id): bool
    {
        return File::find()
            ->byId($id)
            ->exists();
    }

    public function save(FileDTO $fileDTO): int
    {
        $fileModel = new File();

        if ($fileDTO->getId()) {
            try {
                $fileModel = $this->findById($fileDTO->getId());
            } catch (NotFoundHttpException) {
            }
        }

        $fileModel->name = $fileDTO->getFilename() ?: null;
        $fileModel->extension = $fileDTO->getExtension() ?: null;
        $fileModel->path = $fileDTO->getPathToFile() ?: null;
        $fileModel->mime = $fileDTO->getMime() ?: null;
        $fileModel->size = $fileDTO->getSize() ?: null;
        $fileModel->mtime = $fileDTO->getMtime()?->format('Y-m-d H:i:s');
        $fileModel->ctime = $fileDTO->getCtime()?->format('Y-m-d H:i:s');

        try {
            $resultSave = $fileModel->save(false);

            if (!$resultSave) {
                throw new DomainException('Failed saving');
            }
        } catch (Throwable $exception) {
            throw new RuntimeException('Failed saving file information', $exception->getCode(), $exception);
        }

        return $fileModel->id;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function delete(int $id): void
    {
        $fileModel = $this->findById($id);

        try {
            if (!$fileModel->delete()) {
                throw new DomainException('Failed deleting');
            }
        } catch (Throwable $exception) {
            throw new RuntimeException('Failed deleting file information', $exception->getCode(), $exception);
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function findOne(int $id): FileDTO
    {
        $fileModel = $this->findById($id);

        return $this->fillDTO($fileModel);
    }

    /**
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function findByFilename(string $filename): FileDTO
    {
        if (empty($filename)) {
            throw new NotFoundHttpException('File information with filename \'' . $filename . '\'  not found');
        }

        $fileModel = File::find()->byName($filename)->one();

        if (!$fileModel) {
            throw new NotFoundHttpException('File information with filename \'' . $filename . '\'  not found');
        }

        return $this->fillDTO($fileModel);
    }

    /**
     * @throws NotFoundHttpException
     */
    private function findById(int $id): File
    {
        $file = File::find()->byId($id)->one();

        if (!$file) {
            throw new NotFoundHttpException('File information with id \'' . $id . '\'  not found');
        }

        return $file;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function findAllByIds(array $ids): FileListDTO
    {
        $files = File::find()->byIds($ids)->all();

        if (!$files) {
            throw new NotFoundHttpException('List of file information not found');
        }

        $fileListDTO = new FileListDTO();

        foreach ($files as $fileModel) {
            $fileListDTO->add($this->fillDTO($fileModel));
        }

        return $fileListDTO;
    }

    /**
     * @throws \Exception
     */
    private function fillDTO(File $fileModel): FileDTO
    {
        $fileDTO = new FileDTO();
        $fileDTO->setId($fileModel->id);
        $fileDTO->setFilename($fileModel->name ?? '');
        $fileDTO->setExtension($fileModel->extension ?? '');
        $fileDTO->setPathToFile($fileModel->path ?? '');
        $fileDTO->setMime($fileModel->mime ?? '');
        $fileDTO->setSize($fileModel->size ?? 0);
        $fileDTO->setMtime((new DateTimeImmutable($fileModel->mtime ?? 'now')));
        $fileDTO->setCtime((new DateTimeImmutable($fileModel->ctime ?? 'now')));

        return $fileDTO;
    }
}
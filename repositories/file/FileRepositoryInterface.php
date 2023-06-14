<?php

namespace app\repositories\file;

use app\services\file\dto\FileDTO;
use app\services\file\dto\FileListDTOInterface;

interface FileRepositoryInterface
{
    /**
     * Check about contains some row of data.
     * @param int $id
     * @return bool
     */
    public function exist(int $id): bool;

    /**
     * Create or Update file information
     * @param FileDTO $fileDTO
     * @return int
     */
    public function save(FileDTO $fileDTO): int;

    /**
     * Delete file information by id
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Find file information by id
     * @param int $id
     * @return FileDTO
     */
    public function findOne(int $id): FileDTO;

    /**
     * Find file information by several id
     * @param array $ids
     * @return FileListDTOInterface
     */
    public function findAllByIds(array $ids): FileListDTOInterface;

    /**
     * Find file information by filename
     * @param string $filename
     * @return FileDTO
     */
    public function findByFilename(string $filename): FileDTO;
}
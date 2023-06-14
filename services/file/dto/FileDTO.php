<?php

namespace app\services\file\dto;

use DateTimeImmutable;

class FileDTO
{
    private ?int $id = null;
    private string $filename;
    private string $extension = '';
    private string $pathToFile = '';
    private string $mime = '';
    private int $size = 0;
    private ?DateTimeImmutable $mtime = null;
    private ?DateTimeImmutable $ctime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    public function getPathToFile(): string
    {
        return $this->pathToFile;
    }

    public function setPathToFile(string $pathToFile): void
    {
        $this->pathToFile = $pathToFile;
    }

    public function getMime(): string
    {
        return $this->mime;
    }

    public function setMime(string $mime): void
    {
        $this->mime = $mime;
    }

    /**
     * @description size in bytes
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size in bytes
     * @return void
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return null|DateTimeImmutable
     */
    public function getMtime(): ?DateTimeImmutable
    {
        return $this->mtime;
    }

    /**
     * @param null|DateTimeImmutable $mtime
     */
    public function setMtime(?DateTimeImmutable $mtime): void
    {
        $this->mtime = $mtime;
    }

    /**
     * @return null|DateTimeImmutable
     */
    public function getCtime(): ?DateTimeImmutable
    {
        return $this->ctime;
    }

    /**
     * @param null|DateTimeImmutable $ctime
     */
    public function setCtime(?DateTimeImmutable $ctime): void
    {
        $this->ctime = $ctime;
    }
}
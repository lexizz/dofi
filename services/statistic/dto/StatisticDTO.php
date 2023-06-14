<?php

namespace app\services\statistic\dto;

use DateTimeImmutable;

class StatisticDTO
{
    private ?int $id = null;
    private ?string $ip = null;
    private int $fileId = 0;
    private int $numberDownloads = 0;
    private DateTimeImmutable $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getIP(): ?string
    {
        return $this->ip;
    }

    public function setIP(?string $ip): void
    {
        $this->ip = $ip;
    }

    public function getFileId(): int
    {
        return $this->fileId;
    }

    public function setFileId(int $fileId): void
    {
        $this->fileId = $fileId;
    }

    public function getNumberDownloads(): int
    {
        return $this->numberDownloads;
    }

    public function setNumberDownloads(int $numberDownloads): void
    {
        $this->numberDownloads = $numberDownloads;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


}
<?php

namespace app\services\file\dto;

class FileListDTO implements FileListDTOInterface
{
    /**
     * @var FileDTO[]
     */
    private array $fileDTOList = [];

    public function add(FileDTO $fileDTO): self
    {
        if (!array_key_exists($fileDTO->getId(), $this->fileDTOList)) {
            $this->fileDTOList[$fileDTO->getId()] = $fileDTO;
        }

        return $this;
    }

    public function offsetGet(int $key): ?FileDTO
    {
        return $this->fileDTOList[$key] ?? null;
    }

    public function rewind(): void
    {
        reset($this->fileDTOList);
    }

    public function current(): ?FileDTO
    {
        return current($this->fileDTOList);
    }

    public function key(): string|int|null
    {
        return key($this->fileDTOList);
    }

    public function next(): void
    {
        next($this->fileDTOList);
    }

    public function valid(): bool
    {
        return isset($this->fileDTOList[$this->key()]);
    }

    public function count(): int
    {
        return count($this->fileDTOList);
    }

    public function hasFileDTO(): bool
    {
        return !empty($this->fileDTOList);
    }
}
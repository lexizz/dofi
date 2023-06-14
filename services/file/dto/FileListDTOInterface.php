<?php

namespace app\services\file\dto;

use Iterator;

interface FileListDTOInterface extends Iterator
{
    public function add(FileDTO $fileDTO): self;

    public function current(): ?FileDTO;

    public function count(): int;

    public function hasFileDTO(): bool;

    public function offsetGet(int $key): ?FileDTO;
}

<?php

namespace app\services\file\saveFile;

interface SaveFileServiceInterface
{
    public function handle(string $directoryForSave): void;
}
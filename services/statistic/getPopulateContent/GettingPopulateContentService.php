<?php

namespace app\services\statistic\getPopulateContent;

use app\repositories\file\FileRepositoryInterface;
use app\repositories\statistic\StatisticRepositoryInterface;

class GettingPopulateContentService implements GettingPopulateContentServiceInterface
{
    private readonly StatisticRepositoryInterface $statisticRepository;
    private readonly FileRepositoryInterface $fileRepository;

    public function __construct(
        StatisticRepositoryInterface $statisticRepository,
        FileRepositoryInterface $fileRepository
    )
    {
        $this->statisticRepository = $statisticRepository;
        $this->fileRepository = $fileRepository;
    }

    public function handle(): array
    {
        $statisticList = $this->statisticRepository->findTopContent();

        $fileListDTO = $this->fileRepository->findAllByIds($statisticList->keys());

        $result = [];
        foreach ($statisticList as $statisticDTO) {
            $fileDTO = $fileListDTO->offsetGet($statisticDTO->getFileId());

            $result[] = [
                'filename' => $fileDTO?->getFilename(),
                'extension' => $fileDTO?->getExtension(),
                'mime' => $fileDTO?->getMime(),
                'size' => round(($fileDTO?->getSize() ?? 0) / 1024 / 1024, 4),
                'mtime' => $fileDTO?->getMtime()->format('Y-m-d H:i:s'),
                'ctime' => $fileDTO?->getCtime()->format('Y-m-d H:i:s'),
                'number_downloads' => $statisticDTO->getNumberDownloads(),
            ];
        }

        return $result;
    }
}
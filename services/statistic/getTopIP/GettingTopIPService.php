<?php

namespace app\services\statistic\getTopIP;

use app\repositories\file\FileRepositoryInterface;
use app\repositories\statistic\StatisticRepositoryInterface;

class GettingTopIPService implements GettingTopIPServiceInterface
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
        $statisticList = $this->statisticRepository->findTopIPWithPopulateContent();

        $fileListDTO = $this->fileRepository->findAllByIds($statisticList->keys());

        $result = [];
        foreach ($statisticList as $statisticDTO) {
            $fileDTO = $fileListDTO->offsetGet($statisticDTO->getFileId());

            $result[] = [
                'ip' => $statisticDTO->getIP(),
                'filename' => $fileDTO?->getFilename(),
                'number_downloads' => $statisticDTO->getNumberDownloads(),
            ];
        }

        return $result;
    }
}
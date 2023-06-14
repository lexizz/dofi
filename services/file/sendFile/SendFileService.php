<?php

namespace app\services\file\sendFile;

use app\repositories\file\FileRepositoryInterface;
use app\repositories\statistic\StatisticRepositoryInterface;
use app\services\statistic\dto\StatisticDTO;
use RuntimeException;
use yii\log\Logger;
use yii\redis\Cache;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

class SendFileService implements SendFileServiceInterface
{
    private const CACHE_DURATION = 86400;

    private Response $response;
    private Request $request;
    private StatisticRepositoryInterface $statisticRepository;
    private FileRepositoryInterface $fileRepository;
    private Cache $cache;
    private Logger $logger;

    public function __construct(
        Request $request,
        Response $response,
        StatisticRepositoryInterface $statisticRepository,
        FileRepositoryInterface $fileRepository,
        Cache $cache,
        Logger $logger,
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->statisticRepository = $statisticRepository;
        $this->fileRepository = $fileRepository;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function handle(string $filename): Response
    {
        if (empty($filename)) {
            throw new BadRequestHttpException('Filename can\'t be empty');
        }

        $fileDTO = $this->cache->get($filename);

        if (empty($fileDTO)) {
            $fileDTO = $this->fileRepository->findByFilename($filename);

            $this->cache->set($filename, $fileDTO, self::CACHE_DURATION);
        }

        $filePath = $fileDTO->getPathToFile();

        if (empty($filePath)) {
            throw new NotFoundHttpException('Path to file not found');
        }

        if (!file_exists($filePath)) {
            $this->fileRepository->delete($fileDTO->getId());
            $this->cache->delete($filename);

            throw new NotFoundHttpException('File \'' . $filename . '\' not found');
        }

        $statisticDTO = new StatisticDTO();
        $statisticDTO->setIP($this->request->getUserIP());
        $statisticDTO->setFileId($fileDTO->getId());

        try {
            $this->statisticRepository->save($statisticDTO);
        } catch (RuntimeException $exception) {
            $this->logger->log('Failed saving statistic: ' . $exception->getMessage(), Logger::LEVEL_ERROR);
        }

        $this->response->format = Response::FORMAT_RAW;

        return $this->response->sendFile($filePath, options: [
            'inline' => false,
        ]);
    }
}
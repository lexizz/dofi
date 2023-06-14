<?php

namespace app\repositories\statistic;

use app\models\Statistic;
use app\services\statistic\dto\StatisticDTO;
use app\services\statistic\dto\StatisticListDTO;
use DateTimeImmutable;
use DomainException;
use Exception;
use RuntimeException;
use Throwable;
use yii\web\NotFoundHttpException;

class StatisticRepository implements StatisticRepositoryInterface
{
    public function exist(int $id): bool
    {
        return Statistic::find()
            ->byId($id)
            ->exists();
    }

    public function save(StatisticDTO $statisticDTO): int
    {
        $statisticModel = new Statistic();

        if ($statisticDTO->getId()) {
            try {
                $statisticModel = $this->findById($statisticDTO->getId());
            } catch (NotFoundHttpException) {
            }
        }

        $statisticModel->ip = $statisticDTO->getIP();
        $statisticModel->file_id = $statisticDTO->getFileId();

        try {
            $resultSave = $statisticModel->save(false);

            if (!$resultSave) {
                throw new DomainException('Failed saving');
            }
        } catch (Throwable $exception) {
            throw new RuntimeException('Failed saving statistic', $exception->getCode(), $exception);
        }

        return $statisticModel->id;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function delete(int $id): void
    {
        $statisticModel = $this->findById($id);

        try {
            if (!$statisticModel->delete()) {
                throw new DomainException('Failed deleting');
            }
        } catch (Throwable $exception) {
            throw new RuntimeException('Failed deleting statistic', $exception->getCode(), $exception);
        }
    }

    /**
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function findOne(int $id): StatisticDTO
    {
        $statisticModel = $this->findById($id);

        return $this->fillDTO($statisticModel);
    }

    /**
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function findByIP(string $ip): StatisticDto
    {
        if (empty($ip)) {
            throw new NotFoundHttpException('Statistic with filename = ' . $ip . '  not found');
        }

        $statisticModel = Statistic::find()->byIP($ip)->one();

        if (!$statisticModel) {
            throw new NotFoundHttpException('Statistic with filename = ' . $ip . '  not found');
        }

        return $this->fillDTO($statisticModel);
    }

    /**
     * @throws Exception
     */
    public function findTopIPWithPopulateContent(int $limit = 5): StatisticListDTO
    {
        $subQuery = Statistic::find()
            ->select([
                'file_id',
                'ip',
                'COUNT(*) AS number_downloads',
                'ROW_NUMBER() OVER (PARTITION BY file_id ORDER BY COUNT(*) DESC) AS row_num',
            ])
            ->from(Statistic::tableName())
            ->where(['IS NOT', 'file_id', null])
            ->groupBy(['file_id', 'ip']);

        $query = Statistic::find()
            ->select(['file_id', 'ip', 'number_downloads'])
            ->from(['subquery' => $subQuery])
            ->where(['row_num' => 1])
            ->orderBy(['number_downloads' => SORT_DESC]);

        if ($limit > 0) {
            $query->limit($limit);
        }

        /**
         * @var Statistic[] $statisticModels
         */
        $statisticModels = $query->all();

        $statisticListDTO = new StatisticListDTO('getFileId');

        foreach ($statisticModels as $key => $statisticModel) {
            $statisticListDTO->add($this->fillDTO($statisticModel));

            unset($statisticModels[$key]);
        }

        return $statisticListDTO;
    }

    /**
     * @throws Exception
     */
    public function findTopContent(int $limit = 5): StatisticListDTO
    {
        $query = Statistic::find()
            ->select(['file_id', 'COUNT(file_id) AS number_downloads'])
            ->groupBy('file_id')
            ->orderBy(['number_downloads' => SORT_DESC]);

        if ($limit > 0) {
            $query->limit($limit);
        }

        /**
         * @var Statistic[] $statisticModels
         */
        $statisticModels = $query->all();

        $statisticListDTO = new StatisticListDTO('getFileId');

        foreach ($statisticModels as $key => $statisticModel) {
            $statisticListDTO->add($this->fillDTO($statisticModel));

            unset($statisticModels[$key]);
        }

        return $statisticListDTO;
    }

    /**
     * @throws NotFoundHttpException
     */
    private function findById(int $id): Statistic
    {
        $statisticModel = Statistic::findOne($id);

        if (!$statisticModel) {
            throw new NotFoundHttpException('Statistic with id = ' . $id . '  not found');
        }

        return $statisticModel;
    }

    /**
     * @throws Exception
     */
    private function fillDTO(Statistic $statisticModel): StatisticDTO
    {
        $statisticDTO = new StatisticDTO();
        $statisticDTO->setId($statisticModel->id);
        $statisticDTO->setIP($statisticModel->ip);
        $statisticDTO->setFileId($statisticModel->file_id ?? 0);
        $statisticDTO->setNumberDownloads($statisticModel->number_downloads);

        if (!empty($statisticModel->created_at)) {
            $statisticDTO->setCreatedAt((new DateTimeImmutable($statisticModel->created_at)));
        }

        return $statisticDTO;
    }
}
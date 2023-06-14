<?php

namespace app\repositories\statistic;

use app\services\statistic\dto\StatisticDto;
use app\services\statistic\dto\StatisticListDTOInterface;

interface StatisticRepositoryInterface
{
    /**
     * Check about contains some row of data.
     * @param int $id
     * @return bool
     */
    public function exist(int $id): bool;

    /**
     * Create or Update statistic
     * @param StatisticDto $statisticDTO
     * @return int
     */
    public function save(StatisticDTO $statisticDTO): int;

    /**
     * Delete statistic by id
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Find statistic by id
     * @param int $id
     * @return StatisticDto
     */
    public function findOne(int $id): StatisticDto;

    /**
     * Find statistic by ip
     * @param string $ip
     * @return StatisticDto
     */
    public function findByIP(string $ip): StatisticDto;

    /**
     * Find top ip which downloaded content
     * @param int $limit
     * @return StatisticListDTOInterface
     */
    public function findTopIPWithPopulateContent(int $limit = 5): StatisticListDTOInterface;

    /**
     * Find top content
     * @param int $limit
     * @return StatisticListDTOInterface
     */
    public function findTopContent(int $limit = 5): StatisticListDTOInterface;
}
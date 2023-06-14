<?php

namespace app\services\statistic\dto;

use Iterator;

interface StatisticListDTOInterface extends Iterator
{
    public function add(StatisticDTO $statisticDTO): self;

    public function current(): ?StatisticDTO;

    public function keys(): array;

    public function count(): int;

    public function hasStatisticDTO(): bool;
}

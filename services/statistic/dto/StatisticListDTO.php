<?php

namespace app\services\statistic\dto;

class StatisticListDTO implements StatisticListDTOInterface
{
    private const INDEX_DEFAULT = 'getIP';
    private string $funcNameIndex;

    public function __construct(string $funcNameForIndex = self::INDEX_DEFAULT)
    {
        $this->funcNameIndex = $funcNameForIndex;
    }

    /**
     * @var StatisticDTO[]
     */
    private array $statisticDTOList = [];

    public function add(StatisticDTO $statisticDTO): self
    {
        if ($this->funcNameIndex !== self::INDEX_DEFAULT
            && (!method_exists($statisticDTO, $this->funcNameIndex)
            || empty($statisticDTO->{$this->funcNameIndex}()))
        ) {
            $this->funcNameIndex = self::INDEX_DEFAULT;
        }

        if (!array_key_exists($statisticDTO->{$this->funcNameIndex}(), $this->statisticDTOList)) {
            $this->statisticDTOList[$statisticDTO->{$this->funcNameIndex}()] = $statisticDTO;
        }

        return $this;
    }

    public function rewind(): void
    {
        reset($this->statisticDTOList);
    }

    public function current(): ?StatisticDTO
    {
        return current($this->statisticDTOList) ?: null;
    }

    public function key(): string|int|null
    {
        return key($this->statisticDTOList);
    }

    public function keys(): array
    {
        return array_keys($this->statisticDTOList);
    }

    public function next(): void
    {
        next($this->statisticDTOList);
    }

    public function valid(): bool
    {
        return isset($this->statisticDTOList[$this->key()]);
    }

    public function count(): int
    {
        return count($this->statisticDTOList);
    }

    public function hasStatisticDTO(): bool
    {
        return !empty($this->statisticDTOList);
    }
}
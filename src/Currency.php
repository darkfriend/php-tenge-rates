<?php

namespace Darkfriend\TengeRates;

/**
 * Class Currency
 *
 * Реализует конвертацию для каждой конкретной валюты
 *
 * @package Darkfriend\TengeRates
 */
class Currency
{
    /**
     * @var string Код валюты
     */
    public $name;

    /**
     * @var string Название валюты
     */
    public $fullname;

    /**
     * @var string Дата
     */
    public $date;

    /**
     * @var float
     */
    public $price;

    /**
     * @var float Изменение
     */
    public $change;

    /**
     * @var string Порядок изменения
     */
    public $index;

    /**
     * @var int Количество
     */
    public $quantity;

    /**
     * Currency constructor.
     *
     * @param string $fullname Название валюты
     * @param string $name Код валюты
     * @param string $date Дата
     * @param float $price Цена
     * @param float $change Изменение
     * @param string $index Порядок изменения
     * @param int $quantity Количество
     */
    public function __construct(
        string $fullname,
        string $name,
        string $date,
        float $price,
        float $change,
        string $index,
        int $quantity
    )
    {
        $this->fullname = $fullname;
        $this->name = $name;
        $this->date = $date;
        $this->price = $price;
        $this->change = $change;
        $this->index = $index;
        $this->quantity = $quantity;
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new static(
            $data['fullname'],
            $data['title'],
            $data['date'],
            (float)$data['description'],
            (float)$data['change'],
            $data['index'],
            (int)$data['quant']
        );
    }

    /**
     * @param float $quantity
     * @param int $precision
     * @param int $mode
     * @return float
     */
    public function toTenge(float $quantity, int $precision = 2, int $mode = \PHP_ROUND_HALF_UP): float
    {
        return round($quantity * $this->price / $this->quantity, $precision, $mode);
    }

    /**
     * @param float $quantity
     * @param int $precision
     * @param int $mode
     * @return float
     */
    public function fromTenge(float $quantity, int $precision = 2, int $mode = \PHP_ROUND_HALF_UP): float
    {
        return round($quantity / ($this->price * $this->quantity), $precision, $mode);
    }

    /**
     * Проверяет, актуален ли текущий курс
     *
     * @return bool
     * @throws \Exception
     */
    public function isFresh(): bool
    {
        return new \DateTime($this->date) == (new \DateTime())->setTime(0, 0);
    }
}
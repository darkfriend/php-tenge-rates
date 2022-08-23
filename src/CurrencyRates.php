<?php

namespace Darkfriend\TengeRates;


use LaLit\XML2Array;
use Traversable;

/**
 * Class CurrencyRates
 * @package Darkfriend\TengeRates
 */
class CurrencyRates implements \IteratorAggregate, \Countable
{
    /**
     * Ссылка на все валюты
     */
    const URL_RATES_ALL = 'https://nationalbank.kz/rss/get_rates.cfm';

    /**
     * @var string Ссылка на API Национального Банка Казахстана
     */
    protected $url;

    /**
     * @var Currency[]
     */
    protected $_rates = [];

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var string
     */
    protected $date;

    /**
     * @var bool
     */
    protected $isPath = false;

    /**
     * @var bool
     */
    protected $isSsl = true;

    /**
     * CurrencyRates constructor.
     *
     * @param string $date
     * @param int $timeout Timeout for getting currency data
     * @param string $url url or path
     * @throws \Exception
     */
    public function __construct(
        string $date = '',
        int $timeout = 10,
        string $url = self::URL_RATES_ALL
    )
    {
        $this->date = $date ?: date('Y-m-d');
        $this->timeout = $timeout;
        $this->url = $url;

        if (strpos($url, 'http') === false) {
            $this->isPath = true;
        } else {
            $this->isSsl = strpos($url, 'https') !== false;
        }

        $data = $this->getRates();

        foreach ($data['rates']['item'] as $currencyRate) {
            $currencyTitle = strtoupper($currencyRate['title']);
            $currencyRate['date'] = $this->date;
            $this->_rates[$currencyTitle] = Currency::fromArray($currencyRate);
        }
    }

    /**
     * Метод для конвертации валюты в тенге
     *
     * @param string    $currencyCode   код валюты
     * @param float     $quantity       кол-во переводимой валюты
     *
     * @return float
     * @throws \Exception
     */
    public function convertToTenge(string $currencyCode, float $quantity = 1): float
    {
        return $this->getCurrency($currencyCode)->toTenge($quantity);
    }

    /**
     * Метод для конвертации валюты из тенге
     *
     * @param string    $currencyCode   код валюты
     * @param float     $quantity       кол-во переводимой валюты
     *
     * @return float
     * @throws \Exception
     */
    public function convertFromTenge(string $currencyCode, float $quantity = 1): float
    {
        return $this->getCurrency($currencyCode)->fromTenge($quantity);
    }

    /**
     * Поиск валюты по коду
     *
     * @param string $currencyCode
     * @return Currency
     * @throws \Exception
     */
    public function getCurrency(string $currencyCode): Currency
    {
        $currencyCode = strtoupper($currencyCode);

        // Т.к. Нацбанк Казахстана ранее использовал устаревший код RUR, теперь меняем его на RUB
        if ($currencyCode === 'RUR' && !empty($this->_rates['RUB'])) {
            $currencyCode = 'RUB';
        }

        if (!empty($this->_rates[$currencyCode])) {
           return $this->_rates[$currencyCode];
        }

        throw new \Exception('Undefined currency code');
    }

    /**
     * Конвертирует XML с курсом валют в массив
     *
     * @return array
     * @throws \Exception
     */
    public function getRates(): array
    {
        $options = stream_context_create([
            $this->isSsl ? 'https' : 'http' => ['timeout' => $this->timeout]
        ]);

        if ($this->isPath) {
            $url = $this->url;
        } else {
            $url = $this->url
                . '?'
                . http_build_query([
                    'fdate' => date('d.m.Y', strtotime($this->date))
                ]);
        }

        $data = file_get_contents($url, false, $options);

        return XML2Array::createArray($data);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_rates);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->_rates);
    }
}

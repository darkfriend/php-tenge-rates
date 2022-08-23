# Курсы валют от Нацбанка Казахстана

[![PHP version](https://badge.fury.io/ph/darkfriend%2Fphp-tenge-rates.svg)](https://badge.fury.io/ph/darkfriend%2Fphp-tenge-rates)
[![Travis CI build](https://api.travis-ci.org/darkfriend/php-tenge-rates.svg?branch=master "Travis CI build")](https://travis-ci.org/darkfriend/php-tenge-rates)
[![Code Climate](https://codeclimate.com/github/darkfriend/php-tenge-rates/badges/gpa.svg)](https://codeclimate.com/github/darkfriend/php-tenge-rates)
[![Test Coverage](https://codeclimate.com/darkfriend/naffiq/php-tenge-rates/badges/coverage.svg)](https://codeclimate.com/github/darkfriend/php-tenge-rates/coverage)
[![Issue Count](https://codeclimate.com/github/darkfriend/php-tenge-rates/badges/issue_count.svg)](https://codeclimate.com/github/darkfriend/php-tenge-rates)

Данный компонент является оберткой для обработки курса от Нацбанка.

Актуальный курс доступен по ссылке https://nationalbank.kz/rss/get_rates.cfm?fdate=23.02.2022

## Установка

Предпочтительный способ установки - через composer

```bash
$ composer require darkfriend/php-tenge-rates
```

## Конвертация в тенге

```php
<?php

require __DIR__ . '/vendor/autoload.php';
use Darkfriend\TengeRates\CurrencyRates;

$rates = new CurrencyRates();
echo $rates->convertToTenge('USD', 100);
```

### Конвертация из тенге

```php
<?php

require __DIR__ . '/vendor/autoload.php';
use Darkfriend\TengeRates\CurrencyRates;

$rates = new CurrencyRates('23.08.2022');
echo $rates->convertFromTenge('GBP', 100);
```

Все возможные коды валют:
* AUD
* GBP
* DKK
* AED
* USD
* EUR
* CAD
* CNY
* KWD
* KGS
* LVL
* MDL
* NOK
* SAR
* RUB
* XDR
* SGD
* TRL
* UZS
* UAH
* SEK
* CHF
* EEK
* KRW
* JPY
* BYN
* PLN
* ZAR
* TRY
* HUF
* CZK
* TJS
* HKD
* BRL
* MYR
* AZN
* INR
* THB
* AMD
* GEL
* IRR
* MXN

## Прохождение по валютам
Ниже предоставлен пример кода для прохождения по всем валютам. 
Класс `\Darkfriend\TengeRates\CurrencyRates` имплементирует интерфейсы `\Countable` и `\IteratorAggregate`,
так что с его объектами можно орудовать как с массивами. 

```php
<?php

require __DIR__ . '/vendor/autoload.php';
use Darkfriend\TengeRates\CurrencyRates;

$rates = new CurrencyRates();

/** @var \Darkfriend\TengeRates\Currency $rate */
foreach ($rates as $rate) {
    echo "{$rate->name} - {$rate->price}";
}
```

## Лимит времени запроса

По умолчанию лимит времени запроса стоит 1 секунду. Если вы хотите сменить его, то инициализируйте класс `CurrencyRates` со вторым параметром `$timeout`.

```php
<?php

require __DIR__ . '/vendor/autoload.php';
use Darkfriend\TengeRates\CurrencyRates;
$rates = new CurrencyRates('23.08.2022', 15); // timeout 15 secs
```

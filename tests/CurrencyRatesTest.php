<?php

use Darkfriend\TengeRates\CurrencyRates;
use Darkfriend\TengeRates\Currency;

class CurrencyRatesTest extends \PHPUnit\Framework\TestCase
{
    public function testInitialization()
    {
        $rates = new CurrencyRates('', 1, __DIR__ . '/data/sample.xml');

        $this->assertNotEmpty($rates->convertToTenge('RUB'));
    }

    public function testRSSAvailability()
    {
        $rates = new CurrencyRates('23.08.2022', 2, __DIR__ . '/data/sample.xml');

        $this->assertNotEmpty($rates->convertToTenge('RUB'));
    }

    public function testRSSAllAvailability()
    {
        $rates = new CurrencyRates('23.08.2022', 2, __DIR__ . '/data/sample.xml');

        $this->assertNotEmpty($rates->convertToTenge('RUB'));
    }

    public function testGetRates()
    {
        $rates = new CurrencyRates('', 1, __DIR__ . '/data/sample.xml');

        $this->assertNotEmpty($rates->getCurrency('RUB'));
        $this->assertNotEmpty($rates->getCurrency('RUR'));
    }

    public function testGetRatesThatDoesNotExists()
    {
        $rates = new CurrencyRates('23.08.2022', 1, __DIR__ . '/data/sample.xml');

        $this->expectException('\Exception');
        $rates->getCurrency('DOLLAR');
    }

    public function testConvertToTenge()
    {
        $rates = new CurrencyRates('23.08.2022', 1, __DIR__ . '/data/sample.xml');

        $this->assertEquals(5.43, $rates->convertToTenge('RUR'));
    }

    public function testConvertFromTenge()
    {
        $rates = new CurrencyRates('23.08.2022', 1, __DIR__ . '/data/sample.xml');

        $this->assertEquals(3, $rates->convertFromTenge('RUR', 16.29));
    }

    public function testCountable()
    {
        $rates = new CurrencyRates('23.08.2022', 1, __DIR__ . '/data/sample.xml');

        $this->assertEquals(39, count($rates));
    }

    public function testIteratorAggregate()
    {
        $rates = new CurrencyRates('23.08.2022', 1, __DIR__ . '/data/sample.xml');

        foreach ($rates as $rate) {
            $this->assertTrue($rate instanceof Currency);
        }
    }
}

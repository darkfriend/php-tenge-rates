<?php

use Darkfriend\TengeRates\Currency;

class CurrencyTest extends \PHPUnit\Framework\TestCase
{
    public function testInitialization()
    {
        // This array is copied from XML
        $ruble = Currency::fromArray([
            'fullname' => 'Рубли',
            'title' => 'RUR',
            'date' => '23.08.2022',
            'description' => '5.53',
            'quant' => '1',
            'index' => 'DOWN',
            'change' => '-0.01'
        ]);

        $this->assertTrue($ruble instanceof Currency);
    }

    public function testConvertToTenge()
    {
        $ruble = new Currency(
            'Рубли',
            'RUR',
            '23.08.2022',
            5.53,
            -0.01,
            'DOWN',
            1
        );

        $this->assertEquals(11.06, $ruble->toTenge(2));
    }

    public function testConvertFromTenge()
    {
        $ruble = new Currency(
            'Рубли',
            'RUR',
            '23.08.2022',
            5.53,
            -0.01,
            'DOWN',
            1
        );

        $this->assertEquals(3, $ruble->fromTenge(16.59));
    }

    public function testIsFresh()
    {
        $ruble = new Currency(
            'Рубли',
            'RUR',
            '16.06.17',
            5.53,
            -0.01,
            'DOWN',
            1
        );

        $this->assertFalse($ruble->isFresh());
    }
}
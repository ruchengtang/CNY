<?php

namespace Ruchengtang\CNY\Tests;

use PHPUnit\Framework\TestCase;
use Ruchengtang\CNY\CNY;
use Ruchengtang\CNY\Exception\ErrorCode;
use Ruchengtang\CNY\Exception\InvalidArgumentException;
use Ruchengtang\CNY\Exception\RangeException;

class CNYTest extends TestCase
{
    /**
     * @dataProvider emptyCnyProvider
     */
    public function testConvertOrFailWithEmptyCny($value)
    {
        $cny = new CNY();

        try {
            $cny->convertOrFail($value);

            $this->fail('Exception should have been raised!');
        } catch (InvalidArgumentException $e) {
            $this->assertSame(ErrorCode::INVALID_ARGUMENT_EMPTY_CNY, $e->getCode());
            $this->assertSame('Cny must not be empty!', $e->getMessage());
            $this->assertSame((string)$value, $e->originCny());
            $this->assertSame(false, is_null($e->cny()));
        }
    }

    /**
     * @dataProvider invalidCharactersCnyProvider
     */
    public function testConvertOrFailInvalidCharactersCny($value)
    {
        $cny = new CNY();

        try {
            $cny->convertOrFail($value);

            $this->fail('Exception should have been raised!');
        } catch (InvalidArgumentException $e) {
            $this->assertSame(ErrorCode::INVALID_ARGUMENT_INVALID_CHARACTERS_CNY, $e->getCode());
            $this->assertSame(sprintf('Cny %s contains invalid characters!', $value), $e->getMessage());
            $this->assertSame((string)$value, $e->originCny());
            $this->assertSame(false, is_null($e->cny()));
        }
    }

    /**
     * @dataProvider invalidFormatCnyProvider
     */
    public function testConvertOrFailInvalidFormatCny($value)
    {
        $cny = new CNY();

        try {
            $cny->convertOrFail($value);

            $this->fail('Exception should have been raised!');
        } catch (InvalidArgumentException $e) {
            $this->assertSame(ErrorCode::INVALID_ARGUMENT_INVALID_FORMAT_CNY, $e->getCode());
            $this->assertSame(sprintf('Cny %s invalid format!', $value), $e->getMessage());
            $this->assertSame((string)$value, $e->originCny());
            $this->assertSame(false, is_null($e->cny()));
        }
    }

    public function testConvertOrFailTooLargeCny()
    {
        $cny = new CNY();

        $value = 9999999999999.991;

        try {
            $cny->convertOrFail($value);

            $this->fail('Exception should have been raised!');
        } catch (RangeException $e) {
            $this->assertSame(ErrorCode::RANGE_TOO_LARGE_CNY, $e->getCode());
            $this->assertSame(sprintf('Cny %s is too large!', $value), $e->getMessage());
            $this->assertSame((string)$value, $e->originCny());
            $this->assertSame(false, is_null($e->cny()));
        }
    }

    /**
     * @dataProvider cnyProvider
     */
    public function testConvertOrFail($value1, $value2)
    {
        $cny = new CNY();

        $this->assertSame('人民币' . $value2, $cny->convertOrFail($value1));
        $this->assertSame($value2, $cny->convertOrFail($value1, false));
    }

    /**
     * @dataProvider emptyCnyProvider
     */
    public function testConvertWithEmptyCny($value)
    {
        $cny = new CNY();

        $this->assertSame('', $cny->convert($value));
    }

    /**
     * @dataProvider invalidCharactersCnyProvider
     */
    public function testConvertInvalidCharactersCny($value)
    {
        $cny = new CNY();

        $this->assertSame('', $cny->convert($value));
    }

    /**
     * @dataProvider invalidFormatCnyProvider
     */
    public function testConvertInvalidFormatCny($value)
    {
        $cny = new CNY();

        $this->assertSame('', $cny->convert($value));
    }

    public function testConvertTooLargeCny()
    {
        $cny = new CNY();

        $value = 9999999999999.991;

        $this->assertSame('', $cny->convert($value));
    }

    /**
     * @dataProvider cnyProvider
     */
    public function testConvert($value1, $value2)
    {
        $cny = new CNY();

        $this->assertSame('人民币' . $value2, $cny->convert($value1));
        $this->assertSame($value2, $cny->convert($value1, false));
    }

    public function emptyCnyProvider()
    {
        return [
            [''],
            [null],
        ];
    }

    public function invalidCharactersCnyProvider()
    {
        return [
            [' '],
            ['12 '],
            ['12-'],
            ['a.49'],
        ];
    }

    public function invalidFormatCnyProvider()
    {
        return [
            ['1,223.'],
            ['1,23'],
            ['0.3,978'],
            [',23'],
            ['1,22,3'],
            ['8,,12'],
            ['36.'],
            ['..78'],
        ];
    }

    public function cnyProvider()
    {
        return [
            [
                '911911911',
                '玖亿壹仟壹佰玖拾壹万壹仟玖佰壹拾壹元整',
            ],
            [
                '9999999999999.99',
                '玖万玖仟玖佰玖拾玖亿玖仟玖佰玖拾玖万玖仟玖佰玖拾玖元玖角玖分',
            ],
            [
                '8,901,234,567,890.12',
                '捌万玖仟零壹拾贰亿叁仟肆佰伍拾陆万柒仟捌佰玖拾元壹角贰分',
            ],
            [
                '0',
                '零元整',
            ],
            [
                '0.08',
                '捌分',
            ],
            [
                '808',
                '捌佰零捌元整',
            ],
            [
                '8008',
                '捌仟零捌元整',
            ],
            [
                '80000',
                '捌万元整',
            ],
            [
                '80008',
                '捌万零捌元整',
            ],
            [
                '800008',
                '捌拾万零捌元整',
            ],
            [
                '.8',
                '捌角',
            ],
            [
                '.88',
                '捌角捌分',
            ],
            [
                '.888',
                '捌角捌分',
            ],
            [
                '8.888',
                '捌元捌角捌分',
            ],
            [
                '8',
                '捌元整',
            ],
            [
                '88',
                '捌拾捌元整',
            ],
            [
                '888',
                '捌佰捌拾捌元整',
            ],
            [
                '8,888',
                '捌仟捌佰捌拾捌元整',
            ],
            [
                '88,888',
                '捌万捌仟捌佰捌拾捌元整',
            ],
            [
                '888,888',
                '捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                '8,888,888',
                '捌佰捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                '88,888,888',
                '捌仟捌佰捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                '888,888,888',
                '捌亿捌仟捌佰捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                '8,888,888,888',
                '捌拾捌亿捌仟捌佰捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                '88,888,888,888',
                '捌佰捌拾捌亿捌仟捌佰捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                '888,888,888,888',
                '捌仟捌佰捌拾捌亿捌仟捌佰捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                '8,888,888,888,888',
                '捌万捌仟捌佰捌拾捌亿捌仟捌佰捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                '-8,888,888,888,888',
                '负捌万捌仟捌佰捌拾捌亿捌仟捌佰捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                -8888888888888,
                '负捌万捌仟捌佰捌拾捌亿捌仟捌佰捌拾捌万捌仟捌佰捌拾捌元整',
            ],
            [
                12345,
                '壹万贰仟叁佰肆拾伍元整',
            ],
            [
                0.39,
                '叁角玖分',
            ],
            [
                .98,
                '玖角捌分',
            ],
        ];
    }
}

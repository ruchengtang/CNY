# CNY

CNY is a php library helps you convert RMB from digital amount to uppercase. 


## Requirement

1. PHP >= 5.4


## Installation

You can install the package via composer:

```bash
composer require ruchengtang/cny -vvv
```


## Usage

```php
use Ruchengtang\CNY\CNY;

$cny = new CNY();

// An empty string will be returned if the conversion fails:
$cny->convert(8888);
```

or

```php
use Ruchengtang\CNY\CNY;
use Ruchengtang\CNY\Exception\ExceptionInterface;

$cny = new CNY();

try {
    // Conversion failure will throw an exception:
    $cny->convertOrFail(8888);
} catch (ExceptionInterface $e) {
    // ...
}
```


### Supported formats for digital amounts

```php
// String is welcome:
$cny->convert('911911911'); // 人民币玖亿壹仟壹佰玖拾壹万壹仟玖佰壹拾壹元
$cny->convert('8,901,234,567,890.12'); // 人民币捌万玖仟零壹拾贰亿叁仟肆佰伍拾陆万柒仟捌佰玖拾元壹角贰分
$cny->convert('.79'); // 人民币柒角玖分

// Decimal digits are supported:
$cny->convert(8888); // 人民币捌仟捌佰捌拾捌元
$cny->convert(0.36); // 人民币叁角陆分
$cny->convert(.23); // 人民币贰角叁分

// Support negative amounts:
$cny->convert('-8,888,888,888,888'); // 人民币负捌万捌仟捌佰捌拾捌亿捌仟捌佰捌拾捌万捌仟捌佰捌拾捌元
$cny->convert(-0.98); // 人民币负玖角捌分
$cny->convert(-1314); // 人民币负壹仟叁佰壹拾肆元

// Support up to 2 decimals:
$cny->convert(0.3978); // 人民币叁角玖分
$cny->convert('0.397,8'); // 人民币叁角玖分
$cny->convert(0.39); // 人民币叁角玖分
```


### Unsupported formats for digital amounts

```php
// Unsupported empty:
$cny->convertOrFail(''); // Ruchengtang\CNY\Exception\InvalidArgumentException: Cny must not be empty!
$cny->convertOrFail(null); // Ruchengtang\CNY\Exception\InvalidArgumentException: Cny must not be empty!

// Unsupported invalid characters:
$cny->convertOrFail(' '); // Ruchengtang\CNY\Exception\InvalidArgumentException: Cny contains invalid characters!
$cny->convertOrFail('12 '); // Ruchengtang\CNY\Exception\InvalidArgumentException: Cny 12 contains invalid characters!
$cny->convertOrFail('a.49'); // Ruchengtang\CNY\Exception\InvalidArgumentException: Cny a.49 contains invalid characters!

// Unsupported invalid format:
$cny->convertOrFail('1,23'); // Ruchengtang\CNY\Exception\InvalidArgumentException: Cny 1,23 invalid format!
$cny->convertOrFail('0.3,978'); // Ruchengtang\CNY\Exception\InvalidArgumentException: Cny 0.3,978 invalid format!
$cny->convertOrFail('1,22,3'); // Ruchengtang\CNY\Exception\InvalidArgumentException: Cny 1,22,3 invalid format!

// Unsupported too large, maximum support to 9999999999999.99:
$cny->convertOrFail(9999999999999.991); // Ruchengtang\CNY\Exception\RangeException: Cny 1.0E+13 contains invalid characters!
```

### Don't display `人民币` symbol

Display `人民币` symbol by default, If you wish not to display:

```php
$cny->convert(8888, false); // 捌仟捌佰捌拾捌元
$cny->convertOrFail(8888, false); // 捌仟捌佰捌拾捌元
```


## Available Methods

The following methods are available:


##### Ruchengtang\CNY\CNYInterface

```php
<?php

namespace Ruchengtang\CNY;

interface CNYInterface
{
    /**
     * Convert CNY from digital amount to uppercase or throw an exception.
     *
     * @param string|int|float $cny    CNY digital
     * @param bool             $symbol CNY symbol
     *
     * @return string
     */
    public function convertOrFail($cny, $symbol = true);

    /**
     * Convert CNY from digital amount to uppercase.
     *
     * @param string|int|float $cny    CNY digital
     * @param bool             $symbol CNY symbol
     *
     * @return string
     */
    public function convert($cny, $symbol = true);
}
```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Testing

```bash
composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Security

If you discover any security related issues, please email ruchengtang@gmail.com instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


<?php

namespace Ruchengtang\CNY;

use Ruchengtang\CNY\Exception\InvalidArgumentException;
use Ruchengtang\CNY\Exception\RangeException;

final class CNY implements CNYInterface
{
    // Constants:
    const MAXIMUM_NUMBER = 9999999999999.99;

    // Predefine the radix characters and currency symbols for output:
    const CN_ZERO = '零';
    const CN_ONE = '壹';
    const CN_TWO = '贰';
    const CN_THREE = '叁';
    const CN_FOUR = '肆';
    const CN_FIVE = '伍';
    const CN_SIX = '陆';
    const CN_SEVEN = '柒';
    const CN_EIGHT = '捌';
    const CN_NINE = '玖';
    const CN_TEN = '拾';
    const CN_HUNDRED = '佰';
    const CN_THOUSAND = '仟';
    const CN_TEN_THOUSAND = '万';
    const CN_HUNDRED_MILLION = '亿';
    const CN_YUAN = '元';
    const CN_DIME = '角';
    const CN_CENT = '分';
    const CN_INTEGER = '整';
    const CN_SYMBOL = '人民币';
    const CN_NEGATIVE = '负';

    /**
     * Convert CNY from digital amount to uppercase or throw an exception.
     *
     * @param string|int|float $cny    CNY digital
     * @param bool             $symbol CNY symbol
     *
     * @return string
     * @throws InvalidArgumentException
     * @throws RangeException
     */
    public function convertOrFail($cny, $symbol = true)
    {
        $originalCny = (string)$cny;
        $cny = (string)$cny;

        // Check is it negative:
        $isNegative = '-' === substr($cny, 0, 1);

        // Remove the minus sign:
        $cny = $isNegative ? substr($cny, 1) : $cny;

        // Validate input string:
        $this->validate($cny, $originalCny);

        // Normalize the format of input digits:
        $cny = preg_replace('/,/', '', $cny); // Remove comma delimiters.
        $cny = preg_replace('/^0+/', '', $cny); // Trim zeros at the beginning.
        $cny = preg_replace('/^\./', '0.', $cny); // Trim zeros at the beginning.

        // Assert the number is not greater than the maximum number.
        if ((float)$cny > self::MAXIMUM_NUMBER) {
            throw RangeException::forTooLargeCny($cny, $originalCny);
        }

        // Process the conversion from currency digits to characters:
        // Separate integral and decimal parts before processing conversion:
        $parts = explode('.', $cny);
        $integral = $parts[0];
        $decimal = count($parts) > 1 ? $parts[1] : '';
        $decimal = substr($decimal, 0, 2); // Cut down redundant decimal digits that are after the second.

        // Prepare the characters corresponding to the digits:
        $digits = [
            self::CN_ZERO,
            self::CN_ONE,
            self::CN_TWO,
            self::CN_THREE,
            self::CN_FOUR,
            self::CN_FIVE,
            self::CN_SIX,
            self::CN_SEVEN,
            self::CN_EIGHT,
            self::CN_NINE,
        ];
        $radices = [
            '',
            self::CN_TEN,
            self::CN_HUNDRED,
            self::CN_THOUSAND,
        ];
        $bigRadices = [
            '',
            self::CN_TEN_THOUSAND,
            self::CN_HUNDRED_MILLION,
            self::CN_TEN_THOUSAND
        ];
        $decimals = [
            self::CN_DIME,
            self::CN_CENT
        ];

        // Start processing:
        $outputCharacters = '';

        // Process integral part if it is larger than 0:
        if ((int)$integral > 0) {
            $zeroCount = 0;
            $integralLen = strlen($integral);

            for ($i = 0; $i < $integralLen; $i++) {
                $d = substr($integral, $i, 1);
                $p = $integralLen - $i - 1;
                $quotient = (int)floor($p / 4);
                $modulus = $p % 4;

                if ('0' === $d) {
                    $zeroCount++;
                } else {
                    if ($zeroCount > 0) {
                        $outputCharacters .= $digits[0];
                        $zeroCount = 0;
                    }

                    $outputCharacters .= ($digits[(int)$d] . $radices[$modulus]);
                }

                if (0 === $modulus && $zeroCount < 4) {
                    $outputCharacters .= $bigRadices[$quotient];
                }
            }

            $outputCharacters .= self::CN_YUAN;
        }

        // Process decimal part if there is:
        if ('' !== $decimal) {
            for ($i = 0; $i < strlen($decimal); $i++) {
                $d = substr($decimal, $i, 1);

                if ('0' !== $d) {
                    $outputCharacters .= $digits[(int)$d] . $decimals[$i];
                }
            }
        }

        // Confirm and return the final output string:
        if ('' === $outputCharacters) {
            $outputCharacters = $digits[0] . self::CN_YUAN;
        }

        if ('' === $decimal) {
            $outputCharacters .= self::CN_INTEGER;
        }

        $outputCharacters = ($symbol ? self::CN_SYMBOL : '')
            . ($isNegative ? self::CN_NEGATIVE : '')
            . $outputCharacters;

        return $outputCharacters;
    }

    /**
     * Convert CNY from digital amount to uppercase.
     *
     * @param string|int|float $cny    CNY digital
     * @param bool             $symbol CNY symbol
     *
     * @return string
     */
    public function convert($cny, $symbol = true)
    {
        try {
            $outputCharacters = $this->convertOrFail($cny, $symbol);
        } catch (\Exception $e) {
            return '';
        }

        return $outputCharacters;
    }

    /**
     * Validate cny format.
     *
     * @param string           $cny
     * @param string|int|float $originalCny
     *
     * @throws InvalidArgumentException
     */
    private function validate($cny, $originalCny)
    {
        if ('' === $cny) {
            throw InvalidArgumentException::forEmptyCny($cny, $originalCny);
        }

        if (preg_match('/[^,.\d]/', $cny)) {
            throw InvalidArgumentException::forInvalidCharactersCny($cny, $originalCny);
        }

        if (!preg_match('/^(((\d{1,3}(,\d{3})*)?(\.((\d{3},)*\d{1,3}))?)|(\d+(\.\d+)?))$/', $cny)) {
            throw InvalidArgumentException::forInvalidFormatCny($cny, $originalCny);
        }
    }
}

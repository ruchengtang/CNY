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

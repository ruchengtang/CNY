<?php

namespace Ruchengtang\CNY\Exception;

class RangeException extends \RangeException implements ExceptionInterface
{
    private $cny;
    private $originalCny;

    public static function forTooLargeCny($cny, $originalCny)
    {
        $ex = new self(sprintf('Cny %s is too large!', $originalCny), ErrorCode::RANGE_TOO_LARGE_CNY);
        $ex->cny = $cny;
        $ex->originalCny = $originalCny;

        return $ex;
    }

    public function cny()
    {
        return $this->cny;
    }

    public function originCny()
    {
        return $this->originalCny;
    }
}

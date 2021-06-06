<?php

namespace Ruchengtang\CNY\Exception;

class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    private $cny;
    private $originalCny;

    public static function forEmptyCny($cny, $originalCny)
    {
        $ex = new self('Cny must not be empty!', ErrorCode::INVALID_ARGUMENT_EMPTY_CNY);
        $ex->cny = $cny;
        $ex->originalCny = $originalCny;

        return $ex;
    }

    public static function forInvalidCharactersCny($cny, $originalCny)
    {
        $ex = new self(sprintf('Cny %s contains invalid characters!', $originalCny),
            ErrorCode::INVALID_ARGUMENT_INVALID_CHARACTERS_CNY);
        $ex->cny = $cny;
        $ex->originalCny = $originalCny;

        return $ex;
    }

    public static function forInvalidFormatCny($cny, $originalCny)
    {
        $ex = new self(sprintf('Cny %s invalid format!', $originalCny), ErrorCode::INVALID_ARGUMENT_INVALID_FORMAT_CNY);
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

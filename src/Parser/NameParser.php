<?php

namespace Guenther\Guenther\Parser;

class NameParser
{
    protected $pieces = [];

    public function __construct(array $pieces)
    {
        $this->pieces = $this->piecesToLowercase($pieces);
    }

    public function getPieces()
    {
        return $this->pieces;
    }

    protected function piecesToLowercase(array $pieces)
    {
        return array_map(function ($piece) {
            return strtolower($piece);
        }, $pieces);
    }

    protected function piecesToUcFirst(array $pieces)
    {
        return array_map(function ($piece) {
            return ucfirst($piece);
        }, $pieces);
    }

    public function getAsUpperCamelCase()
    {
        return implode('', $this->piecesToUcFirst($this->pieces));
    }

    public function getAsTitleCase()
    {
        return implode(' ', $this->piecesToUcFirst($this->pieces));
    }

    public function getAsKebabCase()
    {
        return implode('-', $this->piecesToLowercase($this->pieces));
    }

    public static function parseFromCamelCase($string)
    {
        $pieces = preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY);

        return new self($pieces);
    }

    public static function parseFromTitleCase($string)
    {
        $pieces = explode(' ', $string);

        return new self($pieces);
    }

    public static function parseFromKebabCase($string)
    {
        $pieces = explode('-', $string);

        return new self($pieces);
    }
}

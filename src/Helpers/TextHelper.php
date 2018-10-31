<?php

namespace Helpers;

/**
 * Class TextHelper
 */
class TextHelper
{
    /**
     * CamelCase
     * @param $string
     * @return string
     */
    public static function toCamelCase($string)
    {
        return ucfirst(str_replace(' ', '', ucwords(strtolower(strtr($string, '_-', '  ')))));
    }

    /**
     * lowerCamelCase
     * @param $string
     * @return string
     */
    public static function toLowerCamelCase($string)
    {
        return lcfirst(static::toCamelCase($string));
    }
}

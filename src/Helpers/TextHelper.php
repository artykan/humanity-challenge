<?php

namespace Helpers;

class TextHelper
{
    public static function toCamelCase($string)
    {
        return ucfirst(str_replace(' ', '', ucwords(strtolower(strtr($string, '_-', '  ')))));
    }

    public static function toLowerCamelCase($string)
    {
        return lcfirst(static::toCamelCase($string));
    }
}

<?php

namespace Teral\Translate\Lib\Util;

/**
 * Class Text
 */
class Text
{
    /**
     * @param string $word
     * @return string
     */
    public static function fullTrim($word)
    {
        return trim($word, " \t\n\r\0\x0B\xA0�");
    }

    /**
     * @param string $haystack
     * @param string $search
     * @return bool
     */
    public static function contains($haystack, $search)
    {
        return strpos($haystack, $search) !== false;
    }

    /**
     * @param string $filename
     * @return string
     */
    public static function removeFileExtension($filename)
    {
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
    }

    /**
     * @param string $regex
     * @return string
     */
    public static function escapeForRegex($regex)
    {
        return str_replace('\\\\/', '\/', str_replace('/', '\/', $regex));
    }
}

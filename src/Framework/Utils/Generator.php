<?php

namespace App\Framework\Utils;

class Generator
{
    private static $specialChar = "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,Â,Ê,Î,Ô,Û,Ä,Ë,Ï,Ö,Ü,À,Æ,Ç,É,È,Œ,Ù";
    private static $replacer = "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,a,ae,c,e,e,oe,u";

    public static function createSlug(string $text): string
    {
        $search = explode(",", self::$specialChar);
        $replace = explode(",", self::$replacer);
        $text = str_replace($search, $replace, $text);

        /* Lowercase all the characters */
        $text = strtolower($text);

        /* Avoid whitespace at the beginning and the ending */
        $text = trim($text);

        /* Replace all the characters that are not in a-z or 0-9 by a hyphen */
        $text = preg_replace("/[^a-z0-9]/", "-", $text);
        /* Remove hyphen anywhere it's more than one */
        $text = preg_replace("/[\-]+/", '-', $text);
        $split = str_split($text);
        $count = strlen($text);

        if ($split[$count - 1] !== "-") {
            return $text;
        } else {
            return $split[$count - 1];
        }
    }
}

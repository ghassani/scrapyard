<?php

namespace Spliced\Component\Commerce\Helper\Route;

use Spliced\Component\Commerce\Helper\Helper;

class Slug extends Helper
{
    public static function sluggify($text, $separator = '-')
    {
        if (function_exists('mb_strtolower')) {
            $text = mb_strtolower($text);
        } else {
            $text = strtolower($text);
        }

        // Remove all none word characters
        #$text = preg_replace('/\W/', ' ', $text);

        // More stripping. Replace spaces with dashes
        $text = strtolower(preg_replace('/[^A-Z^a-z^0-9^\/]+/', $separator,
                preg_replace('/([a-z\d])([A-Z])/', '\1_\2',
                        preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2',
                                preg_replace('/::/', '/', $text)))));

        return trim($text, $separator);
    }

}

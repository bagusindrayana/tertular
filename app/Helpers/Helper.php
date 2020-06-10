<?php
namespace App\Helpers;

use Exception;

class Helper {
    public static function active_class($url,$class)
    {
        if (\Request::is($url) || \Request::is($url.'/*')) { 
            return $class;
        }
    }
}
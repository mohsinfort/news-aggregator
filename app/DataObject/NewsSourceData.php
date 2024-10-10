<?php

namespace App\DataObject;

use ReflectionClass;

class NewsSourceData
{
    const NEWSAPI = "News API";
    const NEW_YORK_TIMES = "New York Times";
    const THE_GUARDIAN = "The Guardian";

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();

        return $constants;
    }
}

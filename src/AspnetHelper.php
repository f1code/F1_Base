<?php

namespace F1\Base;

class AspnetHelper
{
    /**
     * Convert a date from the ASP.NET JSON serialization format
     *
     * @param string $date Input in asp.net json format (e.g., \/Date(3493403409)\/)
     * @return \DateTime Converted date
     */
    public static function convertFromAspnetDate($date)
    {
        $t = intval(substr($date, 6));
        return new \DateTime($t);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 6/7/2015
 * Time: 6:49 PM
 */

namespace F1\Base;


class ArrayHelper
{
    /**
     * Return first object in array such that obj->property === $value
     *
     * @param array $array
     * @param string $property
     * @param mixed $value
     * @return object
     */
    public static function findObjectByProperty($array, $property, $value)
    {
        foreach ($array as $i) {
            if ($i->{$property} === $value)
                return $i;
        }
        return null;
    }
}
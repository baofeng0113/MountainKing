<?php

/**
 * Define enum data for column `digg` in table `ws_digg`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Enum
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Enum_Digg
{
    const TREAD = 0;

    const CARRY = 1;

    public static function descript($key)
    {
        $iterator = self::iterator();
        foreach ($iterator as $element)
            if ($element['key'] == $key)
                return $element['val'];
        return null;
    }

    public static function keysList()
    {
        $keysList = array();
        $iterator = self::iterator();
        foreach ($iterator as $element)
            $keysList[] = $element['key'];
        return $keysList;
    }

    public static function iterator()
    {
        $iterator = array();
        $iterator[0]['key'] = self::TREAD;
        $iterator[0]['val'] = '踩';
        $iterator[1]['key'] = self::CARRY;
        $iterator[1]['val'] = '顶';
        return $iterator;
    }
}
<?php

/**
 * Define enum data for column `config` in table `ws_contents_extras`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Enum
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Enum_ContentsExtras
{
    const CONTENTS_EXTRA_COMMENT = 'comment';

    const CONTENTS_EXTRA_DIGG = 'digg';

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
        $iterator[0]['key'] = self::CONTENTS_EXTRA_COMMENT;
        $iterator[0]['val'] = '开启评论';
        $iterator[1]['key'] = self::CONTENTS_EXTRA_DIGG;
        $iterator[1]['val'] = '开启评分';
        return $iterator;
    }
}
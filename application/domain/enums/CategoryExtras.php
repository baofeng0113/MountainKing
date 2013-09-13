<?php

/**
 * Define enum data for column `config` in table `ws_category_extras`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Enum
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Enum_CategoryExtras
{
    const CATEGORY_EXTRA_PUBLISH = 'publish';

    const CATEGORY_EXTRA_DIGG = 'digg';

    const CATEGORY_EXTRA_COMMENT = 'comment';

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
        $iterator[0]['key'] = self::CATEGORY_EXTRA_PUBLISH;
        $iterator[0]['val'] = '开启审核';
        $iterator[1]['key'] = self::CATEGORY_EXTRA_DIGG;
        $iterator[1]['val'] = '开启评分';
        $iterator[2]['key'] = self::CATEGORY_EXTRA_COMMENT;
        $iterator[2]['val'] = '开启评论';
        return $iterator;
    }
}
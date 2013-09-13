<?php

/**
 * Define enum data for column `viewtype` in table `ws_contents`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Enum
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Enum_ContentsDetail
{
    const CONTENTS_ARTICLES = 0;

    const CONTENTS_PICTURES = 1;

    const CONTENTS_PRODUCTS = 2;

    const CONTENTS_RESOURCE = 3;

    const CONTENTS_VAMEDIAS = 4;

    const CONTENTS_LOCATION = 5;

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
        $iterator[0]['key'] = self::CONTENTS_ARTICLES;
        $iterator[0]['val'] = '文章内容';
        $iterator[1]['key'] = self::CONTENTS_PICTURES;
        $iterator[1]['val'] = '图片内容';
        $iterator[2]['key'] = self::CONTENTS_PRODUCTS;
        $iterator[2]['val'] = '产品内容';
        $iterator[3]['key'] = self::CONTENTS_RESOURCE;
        $iterator[3]['val'] = '资源内容';
        $iterator[4]['key'] = self::CONTENTS_VAMEDIAS;
        $iterator[4]['val'] = '媒体内容';
        $iterator[5]['key'] = self::CONTENTS_LOCATION;
        $iterator[5]['val'] = '地址跳转';
        return $iterator;
    }
}
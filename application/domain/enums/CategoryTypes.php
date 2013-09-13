<?php

/**
 * Define enum data for column `catetype` in table `ws_category`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Enum
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Enum_CategoryTypes
{
    const CATEGORY_ONLY = 0;

    const ARTICLES_VIEW = 1;

    const ARTICLES_LIST = 2;

    const PICTURES_LIST = 3;

    const PRODUCTS_LIST = 4;

    const RESOURCE_LIST = 5;

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
        $iterator[0]['key'] = self::CATEGORY_ONLY;
        $iterator[0]['val'] = '只是分类';
        $iterator[1]['key'] = self::ARTICLES_VIEW;
        $iterator[1]['val'] = '单篇文章';
        $iterator[2]['key'] = self::ARTICLES_LIST;
        $iterator[2]['val'] = '文章列表';
        $iterator[3]['key'] = self::PICTURES_LIST;
        $iterator[3]['val'] = '图片列表';
        $iterator[4]['key'] = self::PRODUCTS_LIST;
        $iterator[4]['val'] = '产品列表';
        $iterator[5]['key'] = self::RESOURCE_LIST;
        $iterator[5]['val'] = '资源列表';
        return $iterator;
    }
}
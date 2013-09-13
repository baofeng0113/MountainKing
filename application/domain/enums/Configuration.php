<?php

/**
 * Define enum data for column `configname` in table `ws_configuration`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Enum
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Enum_Configuration
{
    const WEBSITE_HTML_TITLE = 'website_html_title';

    const WEBSITE_VISIT_ANALYTICS = 'website_visit_analytics';

    const WEBSITE_CLOSE_SIGN = 'website_close_sign';
    const WEBSITE_CLOSE_TEXT = 'website_close_text';

    const WEBSITE_META_KEYWORDS = 'website_meta_keywords';
    const WEBSITE_META_DESCRIPT = 'website_meta_descript';

    const ATTACHMENT_HOSTNAME = 'attachment_hostname';
    const ATTACHMENT_ROOTPATH = 'attachment_rootpath';

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
        $iterator[0]['key'] = self::WEBSITE_META_KEYWORDS;
        $iterator[0]['val'] = '';
        $iterator[1]['key'] = self::WEBSITE_META_DESCRIPT;
        $iterator[1]['val'] = '';
        $iterator[2]['key'] = self::WEBSITE_CLOSE_SIGN;
        $iterator[2]['val'] = '';
        $iterator[3]['key'] = self::WEBSITE_CLOSE_TEXT;
        $iterator[3]['val'] = '';
        $iterator[4]['key'] = self::WEBSITE_HTML_TITLE;
        $iterator[4]['val'] = '';
        $iterator[5]['key'] = self::ATTACHMENT_HOSTNAME;
        $iterator[5]['val'] = '';
        $iterator[6]['key'] = self::ATTACHMENT_ROOTPATH;
        $iterator[6]['val'] = '';
        $iterator[7]['key'] = self::WEBSITE_VISIT_ANALYTICS;
        $iterator[7]['val'] = '';
        return $iterator;
    }
}
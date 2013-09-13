<?php

/**
 * Define enum data for column `loginresult` in table `cp_admin_loginlog`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Enum
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Enum_LoginAuthResult
{
    const PASSED_STRICTLY = 0;

    const FAILED_USERNAME = 1;

    const FAILED_PASSWORD = 2;

    const FAILED_DISABLED = 3;

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
        $iterator[0]['key'] = self::PASSED_STRICTLY;
        $iterator[0]['val'] = "验证通过";
        $iterator[1]['key'] = self::FAILED_USERNAME;
        $iterator[1]['val'] = "无效帐户";
        $iterator[2]['key'] = self::FAILED_PASSWORD;
        $iterator[2]['val'] = "密码错误";
        $iterator[3]['key'] = self::FAILED_DISABLED;
        $iterator[3]['val'] = "帐户禁用";
        return $iterator;
    }
}
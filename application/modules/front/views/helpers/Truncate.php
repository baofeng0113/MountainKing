<?php

class Front_View_Helper_Truncate
{
    public function truncate($string, $length, $sign = "")
    {
        if ($length < 1) return "";
        if (mb_strlen($string) <= $length)
            return $string;
        return mb_substr($string, 0, $length, "UTF-8") . $sign;
    }
}
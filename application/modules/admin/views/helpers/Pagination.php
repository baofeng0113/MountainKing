<?php

class Admin_View_Helper_Pagination
{
    public function pagination($url, $total = 0, $offset = 1, $limit = 30, $ajax = false)
    {
        $pageNumber = ceil($total / $limit);
        $url = str_replace('"', '\'', $url);
        $offset = min(max($offset, 1), $pageNumber);
        $pagingHTML = $ajax ? "<ul class=\"pagination\"><li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', 1, $url) . ";\">首页</a></li><li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', max($offset - 1, 1), $url) . ";\">上一页</a></li>" : "<ul class=\"pagination\"><li><a href=\"" . str_replace('--thisispagingnumberathere--', 1, $url) . "\">首页</a></li><li><a href=\"" . str_replace('--thisispagingnumberathere--', max($offset - 1, 1), $url) . "\">上一页</a></li>";
        if ($pageNumber <= 9) {
            for ($i = 1; $i <= $pageNumber; $i++)
                $pagingHTML .= $i != $offset ? ($ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', $i, $url) . ";\">" . $i . "</a></li>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', $i, $url) . "\">" . $i . "</a></li>") : "<li class=\"active\">" . $i . "</li>";
        } else {
            if ($offset <= 5) {
                for ($i = 1; $i <= 9; $i++)
                    $pagingHTML .= $i != $offset ? ($ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', $i, $url) . ";\">" . $i . "</a></li>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', $i, $url) . "\">" . $i . "</a></li>") : "<li class=\"active\">" . $i . "</li>";
            } elseif ($offset >= $pageNumber - 4) {
                for ($i = $pageNumber - 8; $i <= $pageNumber; $i++)
                    $pagingHTML .= $i != $offset ? ($ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', $i, $url) . ";\">" . $i . "</a></li>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', $i, $url) . "\">" . $i . "</a></li>") : "<li class=\"active\">" . $i . "</li>";
            }
            else {
                $pointer = -4;
                for ($i = 1; $i <= 9; $i++) {
                    $pagingHTML .= $pointer != 0 ? ($ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', $offset + $pointer, $url) . ";\">" . ($offset + $pointer) . "</a></li>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', $offset + $pointer, $url) . "\">" . ($offset + $pointer) . "</a></li>") : "<li class=\"active\">" . ($offset + $pointer) . "</li>";
                    $pointer++;
                }
            }
        }
        return $pagingHTML .= $ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', min($offset + 1, $pageNumber), $url) . "\">下一页</a></li><li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', min($offset + 1, $pageNumber), $url) . ";\">尾页</a></li></ul>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', min($offset + 1, $pageNumber), $url) . "\">下一页</a></li><li><a href=\"" . str_replace('--thisispagingnumberathere--', $pageNumber, $url) . "\">尾页</a></li><li>共&nbsp;" . $total . "条&nbsp;/&nbsp;" . $pageNumber . "页</li></ul>";
    }
    
    public function pagination($url, $total = 0, $offset = 1, $limit = 30, $ajax = false)
    {
        $pageNumber = ceil($total / $limit);
        $url = str_replace('"', '\'', $url);
        $offset = min(max($offset, 1), $pageNumber);
        $pagingHTML = $ajax ? "<ul class=\"pagination\"><li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', 1, $url) . ";\">首页</a></li><li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', max($offset - 1, 1), $url) . ";\">上一页</a></li>" : "<ul class=\"pagination\"><li><a href=\"" . str_replace('--thisispagingnumberathere--', 1, $url) . "\">首页</a></li><li><a href=\"" . str_replace('--thisispagingnumberathere--', max($offset - 1, 1), $url) . "\">上一页</a></li>";
        if ($pageNumber <= 9) {
            for ($i = 1; $i <= $pageNumber; $i++)
                $pagingHTML .= $i != $offset ? ($ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', $i, $url) . ";\">" . $i . "</a></li>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', $i, $url) . "\">" . $i . "</a></li>") : "<li class=\"active\">" . $i . "</li>";
        } else {
        if ($offset <= 5) {
        for ($i = 1; $i <= 9; $i++)
            $pagingHTML .= $i != $offset ? ($ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', $i, $url) . ";\">" . $i . "</a></li>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', $i, $url) . "\">" . $i . "</a></li>") : "<li class=\"active\">" . $i . "</li>";
        } elseif ($offset >= $pageNumber - 4) {
            for ($i = $pageNumber - 8; $i <= $pageNumber; $i++)
                $pagingHTML .= $i != $offset ? ($ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', $i, $url) . ";\">" . $i . "</a></li>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', $i, $url) . "\">" . $i . "</a></li>") : "<li class=\"active\">" . $i . "</li>";
            }
                                else {
                $pointer = -4;
                for ($i = 1; $i <= 9; $i++) {
                $pagingHTML .= $pointer != 0 ? ($ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', $offset + $pointer, $url) . ";\">" . ($offset + $pointer) . "</a></li>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', $offset + $pointer, $url) . "\">" . ($offset + $pointer) . "</a></li>") : "<li class=\"active\">" . ($offset + $pointer) . "</li>";
                $pointer++;
                }
                }
                }
                return $pagingHTML .= $ajax ? "<li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', min($offset + 1, $pageNumber), $url) . "\">下一页</a></li><li><a href=\"javascript:void(0);\" onclick=\"javascript:" . str_replace('--thisispagingnumberathere--', min($offset + 1, $pageNumber), $url) . ";\">尾页</a></li></ul>" : "<li><a href=\"" . str_replace('--thisispagingnumberathere--', min($offset + 1, $pageNumber), $url) . "\">下一页</a></li><li><a href=\"" . str_replace('--thisispagingnumberathere--', $pageNumber, $url) . "\">尾页</a></li><li>共&nbsp;" . $total . "条&nbsp;/&nbsp;" . $pageNumber . "页</li></ul>";
    }
}
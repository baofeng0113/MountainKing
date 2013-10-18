<?php

class Admin_View_Helper_Navigator extends Zend_View_Helper_Url
{
    public function navigator($naviName, $naviLink)
    {
        if (!$naviName && !$naviLink)
            return "<a class=\"box1\" href=\"" . $this->url(array(
                    "module" => "admin", "controller" => "index", "action" => "index"),
                null, true) . "\" target=\"_parent\">控制面板首页</a>";
        if (!$naviLink)
            return "<a class=\"box1\" href=\"" . $this->url(array(
                    "module" => "admin", "controller" => "index", "action" => "index"),
                null, true) . "\" target=\"_parent\">控制面板首页</a><a class=\"box0\">
			</a><a class=\"box2\" href=\"#\">" . $naviName . "</a>";
        return "<a class=\"box1\" href=\"" . $this->url(array("module" => "admin", "controller" => "index",
            "action" => "index"), null, true) . "\" target=\"_parent\">控制面板首页</a>
			<a class=\"box0\"></a><a class=\"box2\" href=\"{$naviLink}\">{$naviName}</a>";
    }
}
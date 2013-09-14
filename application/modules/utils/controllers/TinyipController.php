<?php

class Utils_TinyipController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noViewRenderer', true);
        $request = new Zend_Controller_Request_Http();
        $ip = $request->getParam("ip", null);
        $domainUtilTinyIp = new Domain_Util_TinyIp(
            APPLICATION_PATH . "/../repository/tinyip/tinyip.dat");
        $location = $domainUtilTinyIp->convert($ip === null ||
            $ip === "" ? $request->getClientIp() : $ip);
        header("Content-Type", "text/plain; charset=UTF-8");
        echo $location; exit();
    }
}
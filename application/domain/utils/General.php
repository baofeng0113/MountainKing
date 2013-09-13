<?php

class Domain_Util_General
{
    public static function setLoginAdministrator($username)
    {
        Zend_Session::start();
        $loginSessionNamespace = new Zend_Session_Namespace("administrator");
        $loginSessionNamespace->username = $username;
    }

    public static function getLoginAdministrator()
    {
        Zend_Session::start();
        $loginSessionNamespace = new Zend_Session_Namespace("administrator");
        return isset($loginSessionNamespace->username) ?
            $loginSessionNamespace->username : null;
    }

    public static function delLoginAdministrator()
    {
        Zend_Session::start();
        $loginSessionNamespace = new Zend_Session_Namespace("administrator");
        if (isset($loginSessionNamespace->username))
            unset($loginSessionNamespace->username);
    }

    public static function logger($request, $exception, $priority)
    {
        $logger = Zend_Registry::get("logger");
        $logger->setEventItem("module", $request->getModuleName());
        $logger->setEventItem("controller",
            $request->getControllerName());
        $logger->setEventItem("action", $request->getActionName());
        $logger->setEventItem("file", $exception->getFile());
        $logger->setEventItem("line", $exception->getLine());
        $logger->log($exception->getMessage(), $priority);
    }
}
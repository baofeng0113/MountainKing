<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_LoggingController extends Admin_BasicController
{
    public function loginAction()
    {
        $request = new Zend_Controller_Request_Http();
        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);
            $username = $request->getParam("username", null);
            $password = $request->getParam("password", null);

            $username = $username === null ? null : trim($username);
            $password = $password === null ? null : trim($password);

            if (!Domain_Service_Administrator::validateUsername($username))
                exit($this->buildErroResponse("登录失败，您还没有输入用户名或用户名格式错误"));
            if (!Domain_Service_Administrator::validatePassword($password))
                exit($this->buildErroResponse("登录失败，您还没有输入密码或密码格式错误"));

            try {
                $domainServiceAdministrator = new Domain_Service_Administrator();
                if (!$domainServiceAdministrator->loginVerify(strtolower($username), $password)) {
                    exit($this->buildErroResponse("登录失败，用户不存在或密码错误请重新操作"));
                } else {
                    Domain_Util_General::setLoginAdministrator(strtolower($username));
                    exit($this->buildAjaxResponse(true, null));
                }
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
        $domainServiceConfiguration = new Domain_Service_Configuration();
        $this->view->websiteHtmlTitle = $domainServiceConfiguration->getConfigurationView(
            "website_html_title");
    }

    public function logoutAction()
    {
        Domain_Util_General::delLoginAdministrator();
        $this->_forward("login", "logging", "admin");
    }
}
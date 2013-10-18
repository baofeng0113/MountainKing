<?php

class Admin_BasicController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->loginAndPermissionFilter();
    }

    protected function loginAndPermissionFilter()
    {
        $username = Domain_Util_General::getLoginAdministrator();
        $request = new Zend_Controller_Request_Http();

        if ($this->getRequest()->getControllerName() !== "logging" && $username == null) {
            $message = "发生错误，您还没有登录或登录已经失效；请重新登陆";
            if (strtoupper($request->getMethod()) == "GET")
                exit('<script type="text/javascript">window.parent.location.href="' .
                    $this->_helper->url("login", "logging", "admin") . '";</script>');
            else
                exit($this->buildAjaxResponse(false, $message));
        }

        $domainServicePermission = new Domain_Service_Permission();

        try {
            $message = "发生错误，您没有权限进行该项操作；详情请咨询网站管理员";
            if (!$domainServicePermission->getPermissionByLink($username,
                "/" . $this->getRequest()->getControllerName() .
                    "/" . $this->getRequest()->getActionName())
            )
                exit($this->buildErroResponse($message));
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    protected function buildAjaxResponse($flag, $text)
    {
        return json_encode(array("flag" => $flag, "text" => $text));
    }

    protected function buildErroResponse($message)
    {
        $request = new Zend_Controller_Request_Http();
        if (strtoupper($request->getMethod()) == "POST") {
            return json_encode(array("flag" => false, "text" => $message));
        } else {
            $this->view->failing = $message;
            return $this->view->render("failing.phtml");
        }
    }

    protected function disposeCatchedException($exception)
    {
        $message = "发生错误，服务器忙请稍候再试；详细错误信息请查看系统日志";
        Domain_Util_General::logger($this->_request, $exception, Zend_Log::ERR);
        exit($this->buildErroResponse($message));
    }
}
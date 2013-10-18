<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_ManualeditController extends Admin_BasicController
{
    public function createAction()
    {
        $domainServiceManualEdit = new Domain_Service_ManualEdit();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $code = $request->getParam("code", "");
            $name = $request->getParam("name", "");
            $html = $request->getParam("html", "");
            $description = $request->getParam("description", "");

            if (trim($code) == "")
                exit($this->buildErroResponse("添加失败，您输入的区域标签为空或格式错误"));
            if (trim($name) == "")
                exit($this->buildErroResponse("添加失败，您输入的区域名称为空或格式错误"));
            if (trim($html) == "")
                exit($this->buildErroResponse("添加失败，您输入的区域代码为空或格式错误"));

            if ($domainServiceManualEdit->getManualEditView($code) !== null)
                exit($this->buildErroResponse("添加失败，您输入的区域标签已经存在"));

            $entity = new Domain_Entity_ManualEdit();
            $entity->setCode($code);
            $entity->setName($name);
            $entity->setHtml($html);
            $entity->setDescription($description);
            $entity->setCreatedUser(
                Domain_Util_General::getLoginAdministrator());
            $entity->setCreatedIp($request->getClientIp());
            $entity->setCreatedTime(date("Y-m-d H:i:s"));
            $entity->setUpdatedUser(
                Domain_Util_General::getLoginAdministrator());
            $entity->setUpdatedIp($request->getClientIp());
            $entity->setUpdatedTime(date("Y-m-d H:i:s"));

            try {
                $domainServiceManualEdit->addManualEdit($entity);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
    }

    public function listAction()
    {
        try {
            $domainServicePermission = new Domain_Service_Permission();
            $this->view->haveDelPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "manualedit-del");
            $this->view->haveModPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "manualedit-mod");
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }

        $request = new Zend_Controller_Request_Http();

        $search = new Domain_Search_ManualEdit();
        $search->setName(trim($request->getParam("name", "")));
        $search->setCode(trim($request->getParam("code", "")));
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestName = trim($request->getParam("name", ""));
        $this->view->requestCode = trim($request->getParam("code", ""));
        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        try {
            $domainServiceManualEdit = new Domain_Service_ManualEdit();
            $manualEditNums = $domainServiceManualEdit->getManualEditNums($search);
            $this->view->manualEditNums = $manualEditNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($manualEditNums == 0) {
                $this->view->manualEditList = null;
            } else {
                $manualEditList = $domainServiceManualEdit->getManualEditList(
                    $search, ($page - 1) * 10, 10);
                $this->view->manualEditList = $manualEditList;
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function deleteAction()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noViewRenderer', true);
        $request = new Zend_Controller_Request_Http();

        $code = $request->getParam("code", "");
        if (!$code || mb_strlen(trim($code)) == 0)
            exit($this->buildErroResponse("发生错误，系统未获取到区域代码参数；请返回重试"));

        try {
            $domainServiceManualEdit = new Domain_Service_ManualEdit();
            $domainServiceManualEdit->delManualEdit($code);
            exit($this->buildAjaxResponse(true, null));
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function viewAction()
    {
        $domainServiceManualEdit = new Domain_Service_ManualEdit();
        $request = new Zend_Controller_Request_Http();

        $code = $request->getParam("code", null);
        $code = $code === null ? null : trim($code);

        if ($code === null || $code === "")
            exit($this->buildErroResponse("发生错误，系统未获取到区域编码参数；请返回重试"));

        try {
            $manualEdit = $domainServiceManualEdit->getManualEditView($code);
            if ($manualEdit == null)
                exit($this->buildErroResponse("发生错误，您要查看的静态区域不存在；请返回重试"));
            else
                $this->view->manualEdit = $manualEdit;

            $domainServicePermission = new Domain_Service_Permission();
            $this->view->haveModPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "manualedit-mod");
            $this->view->haveDelPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "manualedit-del");
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function editAction()
    {
        $domainServiceManualEdit = new Domain_Service_ManualEdit();
        $request = new Zend_Controller_Request_Http();

        $code = $request->getParam("code", null);
        $code = $code === null ? null : trim($code);

        if ($code === null || $code === "")
            exit($this->buildErroResponse("发生错误，系统未获取到区域编码参数；请返回重试"));

        try {
            $manualEdit = $domainServiceManualEdit->getManualEditView($code);
            if ($manualEdit == null)
                exit($this->buildErroResponse("发生错误，您要查看的静态区域不存在；请返回重试"));
            $this->view->manualEdit = $manualEdit;
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $code = $request->getParam("code", "");
            $name = $request->getParam("name", "");
            $html = $request->getParam("html", "");
            $description = $request->getParam("description", "");

            if (trim($name) == "")
                exit($this->buildErroResponse("编辑失败，您输入的区域名称为空或格式错误"));
            if (trim($html) == "")
                exit($this->buildErroResponse("编辑失败，您输入的区域代码为空或格式错误"));

            $entity = new Domain_Entity_ManualEdit();
            $entity->setCode($code);
            $entity->setName($name);
            $entity->setHtml($html);
            $entity->setDescription($description);
            $entity->setCreatedUser($manualEdit->getCreatedUser());
            $entity->setCreatedIp($manualEdit->getCreatedIp());
            $entity->setCreatedTime($manualEdit->getCreatedTime());
            $entity->setUpdatedUser(
                Domain_Util_General::getLoginAdministrator());
            $entity->setUpdatedIp($request->getClientIp());
            $entity->setUpdatedTime(date("Y-m-d H:i:s"));

            try {
                $domainServiceManualEdit->modManualEdit($entity);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
    }
}
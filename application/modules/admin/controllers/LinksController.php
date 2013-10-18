<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_LinksController extends Admin_BasicController
{
    public function listAction()
    {
        try {
            $domainServicePermission = new Domain_Service_Permission();
            $this->view->haveDelPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "links-del");
            $this->view->haveModPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "links-mod");
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }

        $request = new Zend_Controller_Request_Http();

        $search = new Domain_Search_Links();
        $search->setLinkName(trim($request->getParam("linkname", "")));
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestLinkName = trim($request->getParam("linkname", ""));
        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        try {
            $domainServiceLinks = new Domain_Service_Links();
            $linksNums = $domainServiceLinks->getLinksNums($search);
            $this->view->linksNums = $linksNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($linksNums == 0) {
                $this->view->linksList = null;
            } else {
                $linksList = $domainServiceLinks->getLinksList(
                    $search, ($page - 1) * 10, 10);
                $this->view->linksList = $linksList;
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

        $id = $request->getParam("id", null);
        if (!$id || !is_numeric($id))
            exit($this->buildErroResponse("发生错误，系统未获取到链接ID参数；请返回重试"));

        try {
            $domainServiceLinks = new Domain_Service_Links();
            $domainServiceLinks->delLinks($id);
            exit($this->buildAjaxResponse(true, null));
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function createAction()
    {
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $linkName = $request->getParam("linkname", "");
            $linkLogo = $request->getParam("linklogo", "");
            $location = $request->getParam("location", "");
            $sequence = $request->getParam("sequence", 0);
            $description = $request->getParam("description", "");

            if (trim($linkName) == "")
                exit($this->buildErroResponse("添加失败，您还没有输入链接名称点击确定重试"));
            if (trim($location) == "")
                exit($this->buildErroResponse("添加失败，您还没有输入链接地址点击确定重试"));

            $domainEntityLinks = new Domain_Entity_Links();
            $domainEntityLinks->setLinkName($linkName);
            $domainEntityLinks->setLinkLogo($linkLogo);
            $domainEntityLinks->setLocation($location);
            $domainEntityLinks->setSequence($sequence);
            $domainEntityLinks->setDescription($description);
            $domainEntityLinks->setCreatedUser(
                Domain_Util_General::getLoginAdministrator());
            $domainEntityLinks->setCreatedIp($request->getClientIp());
            $domainEntityLinks->setCreatedTime(date("Y-m-d H:i:s"));
            $domainEntityLinks->setUpdatedUser(
                Domain_Util_General::getLoginAdministrator());
            $domainEntityLinks->setUpdatedIp($request->getClientIp());
            $domainEntityLinks->setUpdatedTime(date("Y-m-d H:i:s"));

            $domainServiceLinks = new Domain_Service_Links();

            try {
                $domainServiceLinks->addLinks($domainEntityLinks);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
    }

    public function viewAction()
    {
        $request = new Zend_Controller_Request_Http();

        $id = $request->getParam("id", null);
        $id = $id === null ? null : trim($id);

        if ($id === null || $id === "")
            exit($this->buildErroResponse("发生错误，系统未获取到链接ID参数；请返回重试"));

        try {
            $domainServiceLinks = new Domain_Service_Links();
            $links = $domainServiceLinks->getLinksView($id);
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }

        if ($links == null)
            exit($this->buildErroResponse("发生错误，您要查看的友情链接不存在；请返回重试"));
        else
            $this->view->links = $links;

        try {
            $domainServicePermission = new Domain_Service_Permission();
            $this->view->haveModPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "links-mod");
            $this->view->haveDelPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "links-del");
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function editAction()
    {
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $id = $request->getParam("id", null);
            $id = $id === null ? null : trim($id);

            if ($id === null || $id === "")
                exit($this->buildErroResponse("发生错误，系统未获取到链接ID参数；请返回重试"));

            try {
                $domainServiceLinks = new Domain_Service_Links();
                $links = $domainServiceLinks->getLinksView($id);
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }

            if ($links == null)
                exit($this->buildErroResponse("发生错误，您要编辑的友情链接不存在；请返回重试"));

            $linkName = $request->getParam("linkname", "");
            $linkLogo = $request->getParam("linklogo", "");
            $location = $request->getParam("location", "");
            $sequence = $request->getParam("sequence", 0);
            $description = $request->getParam("description", "");

            $links = new Domain_Entity_Links();

            $links->setId($id);
            $links->setLinkName($linkName);
            $links->setLinkLogo($linkLogo);
            $links->setLocation($location);
            $links->setSequence($sequence);
            $links->setDescription($description);
            $links->setUpdatedUser(
                Domain_Util_General::getLoginAdministrator());
            $links->setUpdatedIp($request->getClientIp());
            $links->setUpdatedTime(date("Y-m-d H:i:s"));

            try {
                $domainServiceLinks->modLinks($links);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }

        $id = $request->getParam("id", null);
        $id = $id === null ? null : trim($id);

        if ($id === null || $id === "")
            exit($this->buildErroResponse("发生错误，系统未获取到链接ID参数；请返回重试"));

        try {
            $domainServiceLinks = new Domain_Service_Links();
            $links = $domainServiceLinks->getLinksView($id);
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }

        if ($links == null)
            exit($this->buildErroResponse("发生错误，您要编辑的友情链接不存在；请返回重试"));
        else
            $this->view->links = $links;
    }
}
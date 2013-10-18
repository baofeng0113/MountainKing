<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_KeywordsController extends Admin_BasicController
{
    public function listAction()
    {
        try {
            $domainServicePermission = new Domain_Service_Permission();
            $this->view->haveDelPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "keywords-del");
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }

        $request = new Zend_Controller_Request_Http();

        $search = new Domain_Search_Keywords();
        $search->setKeyword(trim($request->getParam("keyword", "")));
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestKeyword = trim($request->getParam("keyword", ""));
        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        try {
            $domainServiceKeywords = new Domain_Service_Keywords();
            $keywordsNums = $domainServiceKeywords->getKeywordsNums($search);
            $this->view->keywordsNums = $keywordsNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($keywordsNums == 0) {
                $this->view->keywordsList = null;
            } else {
                $keywordsList = $domainServiceKeywords->getKeywordsList(
                    $search, ($page - 1) * 50, 50);
                $this->view->keywordsList = $keywordsList;
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

        $keyword = $request->getParam("keyword", null);
        if (!$keyword)
            exit($this->buildErroResponse("发生错误，系统未获取到关键词参数；请返回重试"));

        try {
            $domainServiceKeywords = new Domain_Service_Keywords();
            $domainServiceKeywords->delKeywords($keyword);
            exit($this->buildAjaxResponse(true, null));
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }
}
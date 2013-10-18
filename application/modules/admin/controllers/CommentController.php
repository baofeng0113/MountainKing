<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_CommentController extends Admin_BasicController
{
    public function publishAction()
    {
        $domainServiceComment = new Domain_Service_Comment();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $publish = $request->getParam('publish', array());

            if (!is_array($publish) || sizeof($publish) == 0)
                exit($this->buildErroResponse("审核失败，您至少需要选择一条评论信息进行操作"));

            try {
                foreach ($publish as $key => $val) {
                    $commentId = intval($key);
                    if ($val == '1') {
                        $comment = new Domain_Entity_Comment();
                        $comment->setId($commentId);
                        $comment->setPublish(true);
                        $comment->setUpdatedUser(Domain_Util_General::getLoginAdministrator());
                        $comment->setUpdatedIp($request->getClientIp());
                        $comment->setUpdatedTime(date("Y-m-d H:i:s"));
                        $domainServiceComment->modPublish($comment);
                    } else {
                        $domainServiceComment->delComment($commentId);
                    }
                }
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }

            exit($this->buildAjaxResponse(true, null));
        }

        $search = new Domain_Search_Comment();
        $search->setPublish(false);
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        try {
            $commentNums = $domainServiceComment->getCommentNums($search);
            $this->view->commentNums = $commentNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($commentNums == 0) {
                $this->view->commentList = null;
            } else {
                $commentList = $domainServiceComment->getCommentList(
                    $search, ($page - 1) * 10, 10);
                $this->view->commentList = $commentList;
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function listAction()
    {
        $domainServiceComment = new Domain_Service_Comment();
        $request = new Zend_Controller_Request_Http();

        $search = new Domain_Search_Comment();
        $search->setPublish(true);
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        try {
            $commentNums = $domainServiceComment->getCommentNums($search);
            $this->view->commentNums = $commentNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($commentNums == 0) {
                $this->view->commentList = null;
            } else {
                $commentList = $domainServiceComment->getCommentList(
                    $search, ($page - 1) * 10, 10);
                $this->view->commentList = $commentList;
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function deleteAction()
    {
        $domainServiceComment = new Domain_Service_Comment();
        $request = new Zend_Controller_Request_Http();

        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noViewRenderer', true);

        $id = $request->getParam("id", null);

        if ($id == null)
            exit($this->buildErroResponse("发生错误，系统未获取到评论ID；请返回重试"));

        try {
            $domainServiceComment->delComment(intval($id));
            exit($this->buildAjaxResponse(true, null));
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }
}
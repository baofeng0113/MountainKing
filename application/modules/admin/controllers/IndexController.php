<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_IndexController extends Admin_BasicController
{
    public function indexAction()
    {
        $username = Domain_Util_General::getLoginAdministrator();

        try {
            $search = new Domain_Search_Contents();
            $search->setPublish(false);
            $domainServiceContents = new Domain_Service_Contents();
            $total = $domainServiceContents->getContentsNums($search);
            $this->view->unpublicContentsNums = $total;
            
            $search = new Domain_Search_Comment();
            $search->setPublish(false);
            $domainServiceComment = new Domain_Service_Comment();
            $total = $domainServiceComment->getCommentNums($search);
            $this->view->unpublicCommentNums = $total;
            
            $domainServicePermission = new Domain_Service_Permission();
            $adminAccreditList = $domainServicePermission->getAdminAccreditList($username);
            if ($adminAccreditList == null) {
                $this->view->adminAccreditList = null;
            } else {
                $accreditList = array();
                foreach ($adminAccreditList as $key => $val)
                    $accreditList[$key] = $val->getAuthoriz();
                $this->view->adminAccreditList = $accreditList;
            }
            $domainServiceAdministrator = new Domain_Service_Administrator();
            $adminAccountsEntity = $domainServiceAdministrator->getAdminAccountView($username);
            $this->view->trueName = $adminAccountsEntity->getTrueName();
            $domainServiceConfiguration = new Domain_Service_Configuration();
            $this->view->websiteHtmlTitle = $domainServiceConfiguration->getConfigurationView(
                "website_html_title");
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function adminAction()
    {
        $domainServiceConfiguration = new Domain_Service_Configuration();
        
        $this->view->websiteHtmlTitle = $domainServiceConfiguration->getConfigurationView(
            "website_html_title");
        $this->view->websiteCloseSign = $domainServiceConfiguration->getConfigurationView(
            "website_close_sign");
        $this->view->websiteVisitAnalytics = $domainServiceConfiguration->getConfigurationView(
            "website_visit_analytics");
        $this->view->attachmentHostName = $domainServiceConfiguration->getConfigurationView(
            "attachment_hostname");
        $this->view->attachmentRootPath = $domainServiceConfiguration->getConfigurationView(
            "attachment_rootpath");
        $this->view->websiteMetaKeywords = $domainServiceConfiguration->getConfigurationView(
            "website_meta_keywords");
        $this->view->websiteMetaDescript = $domainServiceConfiguration->getConfigurationView(
            "website_meta_descript");
        
        $domainServiceCategory = new Domain_Service_Category();
        $total = $domainServiceCategory->getCategoryNums(
            new Domain_Search_Category());
        $this->view->categoryNums = $total;
        
        $domainServiceContents = new Domain_Service_Contents();
        $total = $domainServiceContents->getContentsNums(
                        new Domain_Search_Contents());
        $this->view->contentsNums = $total;
        
        
        $search = new Domain_Search_Contents();
        $search->setPublish(false);
        $total = $domainServiceContents->getContentsNums($search);
        $this->view->unpublicContentsNums = $total;
        
        $domainServiceComment = new Domain_Service_Comment();
        $total = $domainServiceComment->getCommentNums(
            new Domain_Search_Comment());
        $this->view->commentNums = $total;
        
        $search = new Domain_Search_Comment();
        $search->setPublish(false);
        $total = $domainServiceComment->getCommentNums($search);
        $this->view->unpublicCommentNums = $total;
        
        $domainServiceAttachment = new Domain_Service_Attachment();
        $total = $domainServiceAttachment->getAttachmentNums(
            new Domain_Search_Attachment());
        $this->view->attachmentNums = $total;
    }
}
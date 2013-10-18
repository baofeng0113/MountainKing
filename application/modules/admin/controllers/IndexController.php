<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_IndexController extends Admin_BasicController
{
    public function indexAction()
    {
        $username = Domain_Util_General::getLoginAdministrator();

        try {
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
    }
}
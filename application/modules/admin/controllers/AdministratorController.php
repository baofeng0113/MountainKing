<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_AdministratorController extends Admin_BasicController
{
    public function passwordAction()
    {
        $domainServiceAdministrator = new Domain_Service_Administrator();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $oldPassword = $request->getParam("old_password", "");
            $newPassword = $request->getParam("new_password", "");
            $cfmPassword = $request->getParam("cfm_password", "");

            if (!Domain_Service_Administrator::validatePassword($oldPassword))
                exit($this->buildErroResponse("修改失败，您输入的当前密码为空或格式错误"));
            if (!Domain_Service_Administrator::validatePassword($newPassword))
                exit($this->buildErroResponse("修改失败，您输入的新密码为空或格式错误"));
            if (!Domain_Service_Administrator::validatePassword($cfmPassword))
                exit($this->buildErroResponse("修改失败，您输入的确认密码为空或格式错误"));
            if ($newPassword !== $cfmPassword)
                exit($this->buildErroResponse("修改失败，您两次输入的密码不一致"));
            if (!$domainServiceAdministrator->passwordVerify(
                Domain_Util_General::getLoginAdministrator(), $oldPassword)
            )
                exit($this->buildErroResponse("修改失败，您输入的当前密码不正确"));

            $entity = new Domain_Entity_AdminAccounts();
            $entity->setUsername(Domain_Util_General::getLoginAdministrator());
            $entity->setPassword($newPassword);
            $entity->setUpdatedTime(date("Y-m-d H:i:s"));
            $entity->setUpdatedIp($request->getClientIp());
            $entity->setUpdatedUser(Domain_Util_General::getLoginAdministrator());

            try {
                $domainServiceAdministrator->modPassword($entity);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
    }

    public function viewAction()
    {
        $domainServiceAdministrator = new Domain_Service_Administrator();
        $request = new Zend_Controller_Request_Http();

        $username = $request->getParam("username", null);
        $username = $username === null ? null : trim($username);

        if ($username === null || $username === "")
            exit($this->buildErroResponse("发生错误，系统未获取到用户名参数；请返回重试"));

        try {
            $adminAccounts = $domainServiceAdministrator->getAdminAccountView($username);
            if ($adminAccounts == null)
                exit($this->buildErroResponse("发生错误，您要查看的用户名不存在；请返回重试"));
            else
                $this->view->adminAccounts = $adminAccounts;

            $domainServicePermission = new Domain_Service_Permission();
            $this->view->haveModPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "administrator-mod");
            $this->view->haveDelPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "administrator-del");
            $this->view->haveAuthorizPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "administrator-authoriz");
            $domainSearchAdminLogingLog = new Domain_Search_AdminLoginLog();
            $domainSearchAdminLogingLog->setCreatedUser($username);
            $adminLoginLog = $domainServiceAdministrator->getAdminLoginLogList(
                $domainSearchAdminLogingLog, 0, 1);
            $this->view->adminLoginLog = $adminLoginLog;
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function editAction()
    {
        $domainServiceAdministrator = new Domain_Service_Administrator();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $username = $request->getParam("username", "");
            $trueName = $request->getParam("truename", "");
            $disabled = $request->getParam("disabled", "");
            $mailBox = $request->getParam("mailbox", "");
            $mobCode = $request->getParam("mobcode", "");
            $telCode = $request->getParam("telcode", "");
            $faxCode = $request->getParam("faxcode", "");
            $description = $request->getParam("description", "");

            if (!Domain_Service_Administrator::validateUsername($username))
                exit($this->buildErroResponse("编辑失败，您还没有输入用户名或用户名格式错误"));
            if (!Domain_Service_Administrator::validateTrueName($trueName))
                exit($this->buildErroResponse("编辑失败，您还没有输入管理员帐号的真实姓名"));
            if ($mailBox && !Domain_Service_Administrator::validateEmail($mailBox))
                exit($this->buildErroResponse("编辑失败，您输入的邮箱地址格式不正确"));
            if (!Domain_Service_Administrator::validateDisabled($disabled))
                exit($this->buildErroResponse("编辑失败，您还没有选择是否启用帐号或数据错误"));

            $entity = new Domain_Entity_AdminAccounts();
            $entity->setUsername($username);
            $entity->setTrueName($trueName);
            $entity->setDisabled($disabled);
            $entity->setMailBox($mailBox);
            $entity->setMobCode($mobCode);
            $entity->setTelCode($telCode);
            $entity->setFaxCode($faxCode);
            $entity->setDescription($description);
            $entity->setDisabled($disabled);
            $entity->setUpdatedUser(
                Domain_Util_General::getLoginAdministrator());
            $entity->setUpdatedIp($request->getClientIp());
            $entity->setUpdatedTime(date("Y-m-d H:i:s"));

            try {
                $domainServiceAdministrator->modAdminAccount($entity);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        } else {
            $username = $request->getParam("username", null);
            $username = $username === null ? null : trim($username);

            if ($username === null || $username === "")
                exit($this->buildErroResponse("发生错误，系统未获取到用户名参数；请返回重试"));

            try {
                $adminAccounts = $domainServiceAdministrator->getAdminAccountView($username);
                if ($adminAccounts == null)
                    exit($this->buildErroResponse("发生错误，您要编辑的用户不存在；请返回重试"));
                else
                    $this->view->adminAccounts = $adminAccounts;

                $domainSearchAdminLoginLog = new Domain_Search_AdminLoginLog();
                $domainSearchAdminLoginLog->setCreatedUser($username);
                $adminLoginLog = $domainServiceAdministrator->getAdminLoginLogList(
                    $domainSearchAdminLoginLog, 0, 1);
                $this->view->adminLoginLog = $adminLoginLog;
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
    }

    public function listAction()
    {
        $domainServiceAdministrator = new Domain_Service_Administrator();
        $request = new Zend_Controller_Request_Http();

        $search = new Domain_Search_AdminAccounts();
        $search->setUsername(trim($request->getParam("username", "")));
        $search->setTrueName(trim($request->getParam("truename", "")));
        $search->setDisabled(trim($request->getParam("disabled", "")));
        $search->setMailBox(trim($request->getParam("mailbox", "")));
        $search->setMobCode(trim($request->getParam("mobcode", "")));
        $search->setTelCode(trim($request->getParam("telcode", "")));
        $search->setFaxCode(trim($request->getParam("faxcode", "")));
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestUsername = trim($request->getParam("username", ""));
        $this->view->requestTrueName = trim($request->getParam("truename", ""));
        $this->view->requestDisabled = trim($request->getParam("disabled", ""));
        $this->view->requestMailBox = trim($request->getParam("mailbox", ""));
        $this->view->requestMobCode = trim($request->getParam("mobcode", ""));
        $this->view->requestTelCode = trim($request->getParam("telcode", ""));
        $this->view->requestFaxCode = trim($request->getParam("faxcode", ""));
        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        try {
            $domainServicePermission = new Domain_Service_Permission();
            $this->view->haveModPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "administrator-mod");
            $this->view->haveDelPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "administrator-del");
            $this->view->haveAuthorizPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "administrator-authoriz");

            $accountsNums = $domainServiceAdministrator->getAdminAccountsNums(
                $search);
            $this->view->accountsNums = $accountsNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($accountsNums == 0) {
                $this->view->accountsList = null;
            } else {
                $accountsList = $domainServiceAdministrator->getAdminAccountsList(
                    $search, ($page - 1) * 10, 10);
                $this->view->accountsList = $accountsList;
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function createAction()
    {
        $domainServiceAdministrator = new Domain_Service_Administrator();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $username = $request->getParam("username", "");
            $password = $request->getParam("password", "");
            $rePasswd = $request->getParam("repasswd", "");
            $trueName = $request->getParam("truename", "");
            $disabled = $request->getParam("disabled", "");
            $mailBox = $request->getParam("mailbox", "");
            $mobCode = $request->getParam("mobcode", "");
            $telCode = $request->getParam("telcode", "");
            $faxCode = $request->getParam("faxcode", "");
            $description = $request->getParam("description", "");

            if (!Domain_Service_Administrator::validateUsername($username))
                exit($this->buildErroResponse("添加失败，您还没有输入用户名或用户名格式错误"));
            if (!Domain_Service_Administrator::validatePassword($password))
                exit($this->buildErroResponse("添加失败，您还没有输入初始密码或密码格式错误"));
            if (!Domain_Service_Administrator::validatePassword($rePasswd))
                exit($this->buildErroResponse("添加失败，您还没有重复输入密码或密码格式错误"));
            if ($password !== $rePasswd)
                exit($this->buildErroResponse("添加失败，您重复输入的密码与初始密码不一致"));
            if (!Domain_Service_Administrator::validateTrueName($trueName))
                exit($this->buildErroResponse("添加失败，您还没有输入管理员帐号的真实姓名"));
            if ($mailBox && !Domain_Service_Administrator::validateEmail($mailBox))
                exit($this->buildErroResponse("添加失败，您输入的邮箱地址格式不正确"));
            if (!Domain_Service_Administrator::validateDisabled($disabled))
                exit($this->buildErroResponse("添加失败，您还没有选择是否启用帐号或数据错误"));

            if ($domainServiceAdministrator->getAdminAccountView($username) !== null)
                exit($this->buildErroResponse("添加失败，您输入的用户名已经存在请重新操作"));

            $entity = new Domain_Entity_AdminAccounts();
            $entity->setUsername($username);
            $entity->setPassword($password);
            $entity->setTrueName($trueName);
            $entity->setDisabled($disabled);
            $entity->setMailBox($mailBox);
            $entity->setMobCode($mobCode);
            $entity->setTelCode($telCode);
            $entity->setFaxCode($faxCode);
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
                $domainServiceAdministrator->addAdminAccount($entity);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
    }

    public function deleteAction()
    {
        $domainServiceAdministrator = new Domain_Service_Administrator();
        $request = new Zend_Controller_Request_Http();

        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noViewRenderer', true);

        $username = $request->getParam("username", null);

        if (!Domain_Service_Administrator::validateUsername($username))
            exit($this->buildErroResponse("删除失败！用户名为空或用户名格式错误"));

        try {
            $domainServiceAdministrator->delAdminAccount($username);
            exit($this->buildAjaxResponse(true, null));
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function authorizAction()
    {
        $domainServiceAdministrator = new Domain_Service_Administrator();
        $domainServicePermission = new Domain_Service_Permission();
        $request = new Zend_Controller_Request_Http();

        $username = $request->getParam("username", "");

        if (!Domain_Service_Administrator::validateUsername($username))
            exit($this->buildErroResponse("发生错误，未获取到用户名或用户名格式错误"));

        try {
            $adminAccounts = $domainServiceAdministrator->getAdminAccountView(
                $username);
            if ($adminAccounts == null)
                exit($this->buildErroResponse("发生错误，您要编辑的用户不存在请返回重试"));
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $accredit = $request->getParam("accredit", array());
            $entities = array();

            foreach ($accredit as $key => $val) {
                $entity = new Domain_Entity_AdminAccredit();
                $entity->setUsername($username);
                $entity->setAuthoriz($val);
                $entity->setCreatedTime(date("Y-m-d H:i:s"));
                $entity->setCreatedIp($request->getClientIp());
                $entity->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());
                $entities[$key] = $entity;
            }

            try {
                $domainServicePermission->deleteAndInsert($username, $entities);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        } else {
            $this->view->adminAccounts = $adminAccounts;

            try {
                $this->view->accreditList = Domain_Service_Permission::convertAdminAccreditList(
                    $domainServicePermission->getAdminAccreditList($username));
                $this->view->permissionList = Domain_Service_Permission::convertPermissionList(
                    $domainServicePermission->getPermissionList());
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
    }

    public function loginlogAction()
    {
        $domainServiceAdministrator = new Domain_Service_Administrator();
        $request = new Zend_Controller_Request_Http();

        $search = new Domain_Search_AdminLoginLog();
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        try {
            $loginLogNums = $domainServiceAdministrator->getAdminLoginLogNums($search);
            $this->view->loginLogNums = $loginLogNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 20;
            $this->view->page = $page;

            if ($loginLogNums == 0) {
                $this->view->loginLogList = null;
            } else {
                $loginLogList = $domainServiceAdministrator->getAdminLoginLogList(
                    $search, ($page - 1) * 20, 20);
                $this->view->loginLogList = $loginLogList;
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }
}
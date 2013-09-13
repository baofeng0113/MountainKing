<?php

/**
 * Implementation class of service layer
 *
 * @license Apache License 2.0
 *
 * @package Domain_Service
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Service_Administrator
{
    private $domainQueryAdminAccounts = null;

    private $domainQueryAdminLoginLog = null;

    public function Domain_Service_Administrator()
    {
        $this->domainQueryAdminAccounts = new Domain_Query_AdminAccounts();
        $this->domainQueryAdminLoginLog = new Domain_Query_AdminLoginLog();
    }

    public function delAdminAccount($username)
    {
        return $this->domainQueryAdminAccounts->deleteRecord($username);
    }

    public function addAdminAccount($entity)
    {
        $cryptKey = md5($entity->getUsername() . rand(111111, 999999) . time());
        $password = $this->cryptPassword($entity->getPassword(), $cryptKey);
        $entity->setCryptKey($cryptKey);
        $entity->setPassword($password);
        return $this->domainQueryAdminAccounts->insertRecord($entity);
    }

    public function modAdminAccount($entity)
    {
        return $this->domainQueryAdminAccounts->updateRecord($entity);
    }

    public function addAdminLoginLog($entity)
    {
        return $this->domainQueryAdminLoginLog->insertRecord($entity);
    }

    public function getAdminAccountView($username)
    {
        return $this->domainQueryAdminAccounts->selectAdminAccountView($username);
    }

    public function getAdminLoginLogList($search, $offset = 0, $limit = 30)
    {
        return $this->domainQueryAdminLoginLog->selectAdminLoginLogList(
            $search, $offset, $limit);
    }

    public function getAdminLoginLogNums($search)
    {
        return $this->domainQueryAdminLoginLog->selectAdminLoginLogNums(
            $search);
    }

    public function getAdminAccountsList($search, $offset = 0, $limit = 30)
    {
        return $this->domainQueryAdminAccounts->selectAdminAccountList($search, $offset, $limit);
    }

    public function getAdminAccountsNums($search)
    {
        return $this->domainQueryAdminAccounts->selectAdminAccountNums($search);
    }

    public function loginVerify($username, $password)
    {
        $adminLoginLogEntity = new Domain_Entity_AdminLoginLog();
        $request = new Zend_Controller_Request_Http();
        $adminAccountsEntity = $this->getAdminAccountView($username);
        if ($adminAccountsEntity == null) {
            $adminLoginLogEntity->setLoginResult(Domain_Enum_LoginAuthResult::FAILED_USERNAME);
            $adminLoginLogEntity->setDescription(Domain_Enum_LoginAuthResult::descript(
                Domain_Enum_LoginAuthResult::FAILED_USERNAME));
            $adminLoginLogEntity->setCreatedTime(date("Y-m-d H:i:s"));
            $adminLoginLogEntity->setCreatedIp($request->getClientIp());
            $adminLoginLogEntity->setCreatedUser($username);
            $this->addAdminLoginLog($adminLoginLogEntity);
            return false;
        } else if ($adminAccountsEntity->getPassword() != $this->cryptPassword(
            $password, $adminAccountsEntity->getCryptKey())
        ) {
            $adminLoginLogEntity->setLoginResult(Domain_Enum_LoginAuthResult::FAILED_PASSWORD);
            $adminLoginLogEntity->setDescription(Domain_Enum_LoginAuthResult::descript(
                Domain_Enum_LoginAuthResult::FAILED_PASSWORD));
            $adminLoginLogEntity->setCreatedTime(date("Y-m-d H:i:s"));
            $adminLoginLogEntity->setCreatedIp($request->getClientIp());
            $adminLoginLogEntity->setCreatedUser($username);
            $this->addAdminLoginLog($adminLoginLogEntity);
            return false;
        } else if ($adminAccountsEntity->getDisabled()) {
            $adminLoginLogEntity->setLoginResult(Domain_Enum_LoginAuthResult::FAILED_DISABLED);
            $adminLoginLogEntity->setDescription(Domain_Enum_LoginAuthResult::descript(
                Domain_Enum_LoginAuthResult::FAILED_DISABLED));
            $adminLoginLogEntity->setCreatedTime(date("Y-m-d H:i:s"));
            $adminLoginLogEntity->setCreatedIp($request->getClientIp());
            $adminLoginLogEntity->setCreatedUser($username);
            $this->addAdminLoginLog($adminLoginLogEntity);
            return false;
        } else {
            $adminLoginLogEntity->setLoginResult(Domain_Enum_LoginAuthResult::PASSED_STRICTLY);
            $adminLoginLogEntity->setDescription(Domain_Enum_LoginAuthResult::descript(
                Domain_Enum_LoginAuthResult::PASSED_STRICTLY));
            $adminLoginLogEntity->setCreatedTime(date("Y-m-d H:i:s"));
            $adminLoginLogEntity->setCreatedIp($request->getClientIp());
            $adminLoginLogEntity->setCreatedUser($username);
            $this->addAdminLoginLog($adminLoginLogEntity);
            return true;
        }
    }

    public function passwordVerify($username, $password)
    {
        $adminAccountsEntity = $this->getAdminAccountView($username);
        if ($adminAccountsEntity == null)
            return false;
        if ($adminAccountsEntity->getPassword() != $this->cryptPassword(
            $password, $adminAccountsEntity->getCryptKey())
        )
            return false;
        return true;
    }

    public function modPassword($entity)
    {
        $adminAccountsEntity = $this->getAdminAccountView($entity->getUsername());
        if ($adminAccountsEntity == null)
            return false;
        $entity->setPassword($this->cryptPassword($entity->getPassword(),
            $adminAccountsEntity->getCryptKey()));
        $this->domainQueryAdminAccounts->updatePassword($entity);
        return true;
    }

    private function cryptPassword($password, $cryptKey)
    {
        return md5($password . $cryptKey);
    }

    public static function validateUsername($username)
    {
        if ($username === null || $username === "") return false;
        $validator = new Zend_Validate_StringLength(array('min' => 5, 'max' => 20));
        if (!$validator->isValid($username))
            return false;
        $validator = new Zend_Validate_Alnum(array('allowWhiteSpace' => false));
        return $validator->isValid($username);
    }

    public static function validateEmail($email)
    {
        if ($email === null || $email === "") return false;
        $validator = new Zend_Validate_EmailAddress();
        return $validator->isValid($email);
    }

    public static function validatePassword($password)
    {
        if ($password === null || $password === "") return false;
        $validator = new Zend_Validate_StringLength(array('min' => 5, 'max' => 20));
        if (!$validator->isValid($password))
            return false;
        $validator = new Zend_Validate_Alnum(array('allowWhiteSpace' => false));
        return $validator->isValid($password);
    }

    public static function validateTrueName($trueName)
    {
        return $trueName === null || $trueName === "" ? false : true;
    }

    public static function validateDisabled($disabled)
    {
        $validator = new Zend_Validate_Int();
        return $validator->isValid($disabled);
    }
}
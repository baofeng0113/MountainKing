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
class Domain_Service_Permission
{
    private $domainQueryAdminAccredit = null;

    private $domainQueryPermission = null;

    private $domainQueryLinkAuthed = null;

    public function Domain_Service_Permission()
    {
        $this->domainQueryAdminAccredit = new Domain_Query_AdminAccredit();

        $this->domainQueryPermission = new Domain_Query_Permission();

        $this->domainQueryLinkAuthed = new Domain_Query_LinkAuthed();
    }

    public function getPermissionByAuth($username, $auth)
    {
        $permission = $this->domainQueryAdminAccredit->selectAdminAccreditView(
            $username, $auth);
        return $permission === null ? false : true;
    }

    public function getPermissionByLink($username, $link)
    {
        $authoriz = $this->domainQueryLinkAuthed->selectLinkAuthedView($link);
        if ($authoriz === null)
            return true;
        else
            return $this->getPermissionByAuth($username, $authoriz->getAuthoriz());
    }

    public function getAdminAccreditList($username)
    {
        return $this->domainQueryAdminAccredit->selectAdminAccreditList($username);
    }

    public static function convertAdminAccreditList($accreditList)
    {
        if ($accreditList == null) return array();
        $converted = array();
        foreach ($accreditList as $key => $val)
            $converted[$key] = $val->getAuthoriz();
        return $converted;
    }

    public function getPermissionList()
    {
        return $this->domainQueryPermission->selectPermissionList();
    }

    public static function convertPermissionList($permissionList)
    {
        if ($permissionList == null) return array();
        $constructed = array();
        foreach ($permissionList as $key => $val) {
            $constructed[$val->getModuleName()][$val->getModuleAuth()]["description"]
                = $val->getDescription();
            $constructed[$val->getModuleName()][$val->getModuleAuth()]["moduleName"]
                = $val->getModuleName();
            $constructed[$val->getModuleName()][$val->getModuleAuth()]["moduleAuth"]
                = $val->getModuleAuth();
        }
        return $constructed;
    }

    public function deleteAndInsert($username, $entities)
    {
        return $this->domainQueryAdminAccredit->deleteAndInsert($username, $entities);
    }
}
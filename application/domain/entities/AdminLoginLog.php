<?php

/**
 * Data Object for table `cp_admin_loginlog`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_AdminLoginLog
{
    private $id = null;

    private $loginResult = null;
    private $description = null;

    private $createdTime = null;
    private $createdIp = null;
    private $createdUser = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getLoginResult()
    {
        return $this->loginResult;
    }

    public function setLoginResult($loginResult)
    {
        $this->loginResult = $loginResult;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }

    public function getCreatedIp()
    {
        return long2ip($this->createdIp);
    }

    public function setCreatedIp($createdIp)
    {
        if (is_numeric($createdIp))
            $this->createdIp = $createdIp;
        else
            $this->createdIp = sprintf("%u", ip2long($createdIp));
    }

    public function getCreatedUser()
    {
        return $this->createdUser;
    }

    public function setCreatedUser($createdUser)
    {
        $this->createdUser = $createdUser;
    }

    public function toArray()
    {
        $object2Array = array();
        if ($this->loginResult !== null)
            $object2Array['loginresult'] = $this->loginResult;
        if ($this->description !== null)
            $object2Array['description'] = $this->description;
        if ($this->id !== null)
            $object2Array['id'] = $this->id;
        if ($this->createdTime !== null)
            $object2Array['created_time'] = $this->createdTime;
        if ($this->createdIp !== null)
            $object2Array['created_ip'] = $this->createdIp;
        if ($this->createdUser !== null)
            $object2Array['created_user'] = $this->createdUser;
        return $object2Array;
    }
}
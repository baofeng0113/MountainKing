<?php

/**
 * Data Object for table `cp_admin_accounts`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_AdminAccounts
{
    private $disabled = null;

    private $username = null;
    private $password = null;
    private $cryptKey = null;
    private $trueName = null;

    private $mailBox = null;
    private $mobCode = null;
    private $telCode = null;
    private $faxCode = null;

    private $description = null;

    private $createdTime = null;
    private $createdIp = null;
    private $createdUser = null;
    private $updatedTime = null;
    private $updatedIp = null;
    private $updatedUser = null;

    public function getDisabled()
    {
        return $this->disabled == 0 ? false : true;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled ? 1 : 0;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getCryptKey()
    {
        return $this->cryptKey;
    }

    public function setCryptKey($cryptKey)
    {
        $this->cryptKey = $cryptKey;
    }

    public function getTrueName()
    {
        return $this->trueName;
    }

    public function setTrueName($trueName)
    {
        $this->trueName = $trueName;
    }

    public function getMailBox()
    {
        return $this->mailBox;
    }

    public function setMailBox($mailBox)
    {
        $this->mailBox = $mailBox;
    }

    public function getMobCode()
    {
        return $this->mobCode;
    }

    public function setMobCode($mobCode)
    {
        $this->mobCode = $mobCode;
    }

    public function getTelCode()
    {
        return $this->telCode;
    }

    public function setTelCode($telCode)
    {
        $this->telCode = $telCode;
    }

    public function getFaxCode()
    {
        return $this->faxCode;
    }

    public function setFaxCode($faxCode)
    {
        $this->faxCode = $faxCode;
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

    public function getUpdatedTime()
    {
        return $this->updatedTime;
    }

    public function setUpdatedTime($updatedTime)
    {
        $this->updatedTime = $updatedTime;
    }

    public function getUpdatedIp()
    {
        return long2ip($this->updatedIp);
    }

    public function setUpdatedIp($updatedIp)
    {
        if (is_numeric($updatedIp))
            $this->updatedIp = $updatedIp;
        else
            $this->updatedIp = sprintf("%u", ip2long($updatedIp));
    }

    public function getUpdatedUser()
    {
        return $this->updatedUser;
    }

    public function setUpdatedUser($updatedUser)
    {
        $this->updatedUser = $updatedUser;
    }

    public function toArray()
    {
        $object2Array = array();
        if ($this->username !== null)
            $object2Array['username'] = $this->username;
        if ($this->password !== null)
            $object2Array['password'] = $this->password;
        if ($this->cryptKey !== null)
            $object2Array['cryptkey'] = $this->cryptKey;
        if ($this->trueName !== null)
            $object2Array['truename'] = $this->trueName;
        if ($this->disabled !== null)
            $object2Array['disabled'] = $this->disabled;
        if ($this->description !== null)
            $object2Array['description'] = $this->description;
        if ($this->mailBox !== null)
            $object2Array['mailbox'] = $this->mailBox;
        if ($this->telCode !== null)
            $object2Array['telcode'] = $this->telCode;
        if ($this->mobCode !== null)
            $object2Array['mobcode'] = $this->mobCode;
        if ($this->faxCode !== null)
            $object2Array['faxcode'] = $this->faxCode;
        if ($this->createdTime !== null)
            $object2Array['created_time'] = $this->createdTime;
        if ($this->createdIp !== null)
            $object2Array['created_ip'] = $this->createdIp;
        if ($this->createdUser !== null)
            $object2Array['created_user'] = $this->createdUser;
        if ($this->updatedTime !== null)
            $object2Array['updated_time'] = $this->updatedTime;
        if ($this->updatedIp !== null)
            $object2Array['updated_ip'] = $this->updatedIp;
        if ($this->updatedUser !== null)
            $object2Array['updated_user'] = $this->updatedUser;
        return $object2Array;
    }
}
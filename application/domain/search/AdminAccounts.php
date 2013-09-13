<?php

/**
 * Request Object for search on table `cp_admin_accounts`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Search
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Search_AdminAccounts
{
    private $username = null;
    private $trueName = null;
    private $disabled = null;

    private $mailBox = null;
    private $mobCode = null;
    private $telCode = null;
    private $faxCode = null;

    private $createdTime1 = null;
    private $createdTime2 = null;
    private $createdIp = null;
    private $createdUser = null;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        if ($username !== "" && $username !== null)
            $this->username = $username;
    }

    public function getTrueName()
    {
        return $this->trueName;
    }

    public function setTrueName($trueName)
    {
        if ($trueName !== "" && $trueName !== null)
            $this->trueName = $trueName;
    }

    public function getDisabled()
    {
        return $this->disabled === null ? null : (
        $this->disabled == 1 ? 1 : 0);
    }

    public function setDisabled($disabled)
    {
        if ($disabled !== "" && $disabled !== null)
            $this->disabled = $disabled ? true : false;
    }

    public function getMailBox()
    {
        return $this->mailBox;
    }

    public function setMailBox($mailBox)
    {
        if ($mailBox !== "" && $mailBox !== null)
            $this->mailBox = $mailBox;
    }

    public function getMobCode()
    {
        return $this->mobCode;
    }

    public function setMobCode($mobCode)
    {
        if ($mobCode !== "" && $mobCode !== null)
            $this->mobCode = $mobCode;
    }

    public function getTelCode()
    {
        return $this->telCode;
    }

    public function setTelCode($telCode)
    {
        if ($telCode !== "" && $telCode !== null)
            $this->telCode = $telCode;
    }

    public function getFaxCode()
    {
        return $this->faxCode;
    }

    public function setFaxCode($faxCode)
    {
        if ($faxCode !== "" && $faxCode !== null)
            $this->faxCode = $faxCode;
    }

    public function getCreatedTime1()
    {
        return $this->createdTime1;
    }

    public function setCreatedTime1($createdTime)
    {
        if ($createdTime !== "" && $createdTime !== null)
            $this->createdTime1 = $createdTime;
    }

    public function getCreatedTime2()
    {
        return $this->createdTime2;
    }

    public function setCreatedTime2($createdTime)
    {
        if ($createdTime !== "" && $createdTime !== null)
            $this->createdTime2 = $createdTime;
    }

    public function getCreatedIp()
    {
        return $this->createdIp;
    }

    public function setCreatedIp($createdIp)
    {
        if ($createdIp !== "" && $createdIp !== null)
            $this->createdIp = $createdIp == null ? null : sprintf(
                "%u", ip2long($createdIp));
    }

    public function getCreatedUser()
    {
        return $this->createdUser;
    }

    public function setCreatedUser($createdUser)
    {
        if ($createdUser !== "" && $createdUser !== null)
            $this->createdUser = $createdUser;
    }
}
<?php

/**
 * Request Object for search on table `ws_links`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Search
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Search_Links
{
    private $linkName = null;

    private $createdTime1 = null;
    private $createdTime2 = null;
    private $createdIp = null;
    private $createdUser = null;

    public function getLinkName()
    {
        return $this->linkName;
    }

    public function setLinkName($linkName)
    {
        $this->linkName = $linkName;
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
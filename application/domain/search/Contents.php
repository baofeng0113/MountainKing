<?php

/**
 * Request Object for search on table `ws_contents`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Search
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Search_Contents
{
    private $category = null;
    private $contents = null;
    private $viewType = null;
    private $disabled = null;

    private $publish = null;

    private $createdTime1 = null;
    private $createdTime2 = null;
    private $createdIp = null;
    private $createdUser = null;

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        if ($category !== "" && $category !== null)
            $this->category = $category;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    public function getViewType()
    {
        return $this->viewType;
    }

    public function setViewType($viewType)
    {
        if ($viewType !== "" && $viewType !== null)
            $this->viewType = $viewType;
    }

    public function getDisabled()
    {
        return $this->disabled === null ? null : (
        $this->disabled === true ? 1 : 0);
    }

    public function setDisabled($disabled)
    {
        if ($disabled !== "" && $disabled !== null)
            $this->disabled = $disabled ? true : false;
    }

    public function getPublish()
    {
        return $this->publish === null ? null : (
        $this->publish === true ? 1 : 0);
    }

    public function setPublish($publish)
    {
        if ($publish !== "" && $publish !== null)
            $this->publish = $publish ? true : false;
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
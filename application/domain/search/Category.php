<?php

/**
 * Request Object for search on table `ws_category`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Search
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Search_Category
{
    private $cateName = null;
    private $cateCode = null;
    private $cateType = null;
    private $disabled = null;

    private $createdTime1 = null;
    private $createdTime2 = null;

    private $createdUser = null;

    public function getCateName()
    {
        return $this->cateName;
    }

    public function setCateName($cateName)
    {
        if ($cateName !== "" && $cateName !== null)
            $this->cateName = $cateName;
    }

    public function getCateCode()
    {
        return $this->cateCode;
    }

    public function setCateCode($cateCode)
    {
        if ($cateCode !== "" && $cateCode !== null)
            $this->cateCode = $cateCode;
    }

    public function getCateType()
    {
        return $this->cateType;
    }

    public function setCateType($cateType)
    {
        if ($cateType !== "" && $cateType !== null)
            $this->cateType = $cateType;
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
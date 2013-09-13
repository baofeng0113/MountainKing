<?php

/**
 * Data Object for table `ws_category`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_Category
{
    private $id = null;

    private $cateName = null;
    private $cateCode = null;
    private $cateLogo = null;
    private $cateType = null;
    private $template = null;
    private $keywords = null;
    private $disabled = null;
    private $sequence = null;

    private $description = null;

    private $createdTime = null;
    private $createdIp = null;
    private $createdUser = null;
    private $updatedTime = null;
    private $updatedIp = null;
    private $updatedUser = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCateName()
    {
        return $this->cateName;
    }

    public function setCateName($cateName)
    {
        $this->cateName = $cateName;
    }

    public function getCateCode()
    {
        return $this->cateCode;
    }

    public function setCateCode($cateCode)
    {
        $this->cateCode = $cateCode;
    }

    public function getCateLogo()
    {
        return $this->cateLogo;
    }

    public function setCateLogo($cateLogo)
    {
        $this->cateLogo = $cateLogo;
    }

    public function getCateType()
    {
        return $this->cateType;
    }

    public function setCateType($cateType)
    {
        $this->cateType = $cateType;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getDisabled()
    {
        return $this->disabled == 0 ? false : true;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled ? 1 : 0;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
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
        if ($this->id !== null)
            $object2Array['id'] = $this->id;
        if ($this->description !== null)
            $object2Array['description'] = $this->description;
        if ($this->cateCode !== null)
            $object2Array['catecode'] = $this->cateCode;
        if ($this->cateName !== null)
            $object2Array['catename'] = $this->cateName;
        if ($this->cateLogo !== null)
            $object2Array['catelogo'] = $this->cateLogo;
        if ($this->cateType !== null)
            $object2Array['catetype'] = $this->cateType;
        if ($this->keywords !== null)
            $object2Array['keywords'] = $this->keywords;
        if ($this->disabled !== null)
            $object2Array['disabled'] = $this->disabled;
        if ($this->sequence !== null)
            $object2Array['sequence'] = $this->sequence;
        if ($this->template !== null)
            $object2Array['template'] = $this->template;
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
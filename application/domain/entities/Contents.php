<?php

/**
 * Data Object for table `ws_contents`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_Contents
{
    private $id = null;

    private $description = null;

    private $category = null;
    private $keywords = null;
    private $disabled = null;
    private $viewType = null;
    private $sequence = null;

    private $subject = null;
    private $publish = null;

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

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getPublish()
    {
        return $this->publish == 0 ? false : true;
    }

    public function setPublish($publish)
    {
        $this->publish = $publish ? 1 : 0;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getDisabled()
    {
        return $this->disabled == 0 ? false : true;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled ? 1 : 0;
    }

    public function getViewType()
    {
        return $this->viewType;
    }

    public function setViewType($viewType)
    {
        $this->viewType = $viewType;
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
        if ($this->description !== null)
            $object2Array['description'] = $this->description;
        if ($this->id !== null)
            $object2Array['id'] = $this->id;
        if ($this->keywords !== null)
            $object2Array['keywords'] = $this->keywords;
        if ($this->viewType !== null)
            $object2Array['viewtype'] = $this->viewType;
        if ($this->disabled !== null)
            $object2Array['disabled'] = $this->disabled;
        if ($this->category !== null)
            $object2Array['category'] = $this->category;
        if ($this->subject !== null)
            $object2Array['subject'] = $this->subject;
        if ($this->sequence !== null)
            $object2Array['sequence'] = $this->sequence;
        if ($this->publish !== null)
            $object2Array['publish'] = $this->publish;
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
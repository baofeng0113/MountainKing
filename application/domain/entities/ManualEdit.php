<?php

/**
 * Data Object for table `ws_manualedit`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_ManualEdit
{
    private $code = null;
    private $name = null;
    private $html = null;

    private $description = null;

    private $createdTime = null;
    private $createdIp = null;
    private $createdUser = null;
    private $updatedTime = null;
    private $updatedIp = null;
    private $updatedUser = null;

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
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
        if ($this->code !== null)
            $object2Array['code'] = $this->code;
        if ($this->name !== null)
            $object2Array['name'] = $this->name;
        if ($this->html !== null)
            $object2Array['html'] = $this->html;
        if ($this->description !== null)
            $object2Array['description'] = $this->description;
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
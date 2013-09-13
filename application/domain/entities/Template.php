<?php

/**
 * Data Object for table `ws_template`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_Template
{
    private $id = null;

    private $themeName = null;
    private $directory = null;
    private $copyright = null;

    private $description = null;

    private $createdTime = null;
    private $createdIp = null;
    private $createdUser = null;
    private $updatedTime = null;
    private $updatedIp = null;
    private $updatedUser = null;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getThemeName()
    {
        return $this->themeName;
    }

    public function setThemeName($themeName)
    {
        $this->themeName = $themeName;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    public function getCopyright()
    {
        return $this->copyright;
    }

    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
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
        if ($this->themeName !== null)
            $object2Array['themename'] = $this->themeName;
        if ($this->directory !== null)
            $object2Array['directory'] = $this->directory;
        if ($this->copyright !== null)
            $object2Array['copyright'] = $this->copyright;
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
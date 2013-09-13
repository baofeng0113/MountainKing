<?php

/**
 * Data Object for table `ws_links`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_Links
{
    private $id = null;

    private $linkName = null;
    private $linkLogo = null;
    private $location = null;
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

    public function getLinkName()
    {
        return $this->linkName;
    }

    public function setLinkName($linkName)
    {
        $this->linkName = $linkName;
    }

    public function getLinkLogo()
    {
        return $this->linkLogo;
    }

    public function setLinkLogo($linkLogo)
    {
        $this->linkLogo = $linkLogo;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
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
        if ($this->linkName !== null)
            $object2Array['linkname'] = $this->linkName;
        if ($this->linkLogo !== null)
            $object2Array['linklogo'] = $this->linkLogo;
        if ($this->location !== null)
            $object2Array['location'] = $this->location;
        if ($this->sequence !== null)
            $object2Array['sequence'] = $this->sequence;
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
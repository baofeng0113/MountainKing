<?php

/**
 * Data Object for table `ws_navigat`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_Navigat
{
    private $id = null;

    private $description = null;

    private $naviName = null;
    private $location = null;
    private $sequence = null;

    private $createdTime = null;
    private $createdIp = null;
    private $createdUser = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getNaviName()
    {
        return $this->naviName;
    }

    public function setNaviName($naviName)
    {
        $this->naviName = $naviName;
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

    public function toArray()
    {
        $object2Array = array();
        if ($this->id !== null)
            $object2Array['id'] = $this->id;
        if ($this->description !== null)
            $object2Array['description'] = $this->description;
        if ($this->naviName !== null)
            $object2Array['naviname'] = $this->naviName;
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
        return $object2Array;
    }
}

?>
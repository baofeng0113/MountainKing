<?php

/**
 * Data Object for table `ws_configuration`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_Configuration
{
    private $configName = null;
    private $configText = null;

    private $createdTime = null;
    private $createdIp = null;
    private $createdUser = null;

    public function getConfigName()
    {
        return $this->configName;
    }

    public function setConfigName($configName)
    {
        $this->configName = $configName;
    }

    public function getConfigText()
    {
        return $this->configText;
    }

    public function setConfigText($configText)
    {
        $this->configText = $configText;
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
        if ($this->configName !== null)
            $object2Array['configname'] = $this->configName;
        if ($this->configText !== null)
            $object2Array['configtext'] = $this->configText;
        if ($this->createdTime !== null)
            $object2Array['created_time'] = $this->createdTime;
        if ($this->createdIp !== null)
            $object2Array['created_ip'] = $this->createdIp;
        if ($this->createdUser !== null)
            $object2Array['created_user'] = $this->createdUser;
        return $object2Array;
    }
}
<?php

/**
 * Data Object for table `ws_category_extras`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_CategoryExtras
{
    private $cateId = null;
    private $config = null;
    private $params = null;

    private $createdTime = null;
    private $createdIp = null;
    private $createdUser = null;

    public function getCateId()
    {
        return $this->cateId;
    }

    public function setCateId($cateId)
    {
        $this->cateId = $cateId;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
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
        if ($this->cateId !== null)
            $object2Array['cateid'] = $this->cateId;
        if ($this->config !== null)
            $object2Array['config'] = $this->config;
        if ($this->params !== null)
            $object2Array['params'] = $this->params;
        if ($this->createdTime !== null)
            $object2Array['created_time'] = $this->createdTime;
        if ($this->createdIp !== null)
            $object2Array['created_ip'] = $this->createdIp;
        if ($this->createdUser !== null)
            $object2Array['created_user'] = $this->createdUser;
        return $object2Array;
    }
}
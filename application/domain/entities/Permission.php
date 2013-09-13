<?php

/**
 * Data Object for table `cp_permission`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_Permission
{
    private $moduleName = null;
    private $moduleAuth = null;

    private $description = null;

    public function getModuleName()
    {
        return $this->moduleName;
    }

    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    public function getModuleAuth()
    {
        return $this->moduleAuth;
    }

    public function setModuleAuth($moduleAuth)
    {
        $this->moduleAuth = $moduleAuth;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function toArray()
    {
        $object2Array = array();
        if ($this->moduleName !== null)
            $object2Array['modulename'] = $this->moduleName;
        if ($this->moduleAuth !== null)
            $object2Array['moduleauth'] = $this->moduleAuth;
        if ($this->description !== null)
            $object2Array['description'] = $this->description;
        return $object2Array;
    }
}
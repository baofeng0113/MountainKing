<?php

/**
 * Data access object for table `cp_permission`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Permission extends Zend_Db_Table
{
    protected $_name = "cp_permission";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Permission();
        $entity->setDescription($record["description"]);
        $entity->setModuleName($record["modulename"]);
        $entity->setModuleAuth($record["moduleauth"]);
        return $entity;
    }

    public function selectPermissionList()
    {
        $result = $this->fetchAll();
        if ($result == null) return null;
        $permissionList = array();
        foreach ($result as $key => $val)
            $permissionList[$key] = $this->convertRecord($val);
        return $permissionList;
    }
}
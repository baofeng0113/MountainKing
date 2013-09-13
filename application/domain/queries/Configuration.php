<?php

/**
 * Data access object for table `ws_configuration`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Configuration extends Zend_Db_Table
{
    protected $_name = "ws_configuration";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Configuration();
        $entity->setConfigName($record["configname"]);
        $entity->setConfigText($record["configtext"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        return $entity;
    }

    public function selectConfigurationList()
    {
        $result = $this->fetchAll();
        $configurationList = array();
        foreach ($result as $key => $val)
            $configurationList[$key] = $this->convertRecord($val);
        return $configurationList;
    }

    public function selectConfigurationView($configName)
    {
        if (!is_string($configName))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("configname = :configname")->bind(
            array(":configname" => $configName));
        $result = $this->fetchRow($select);
        if ($result == null) return null;
        return $this->convertRecord($result);
    }

    private function insertRecord($entity)
    {
        return $this->insert($entity->toArray());
    }

    private function deleteRecord($configName)
    {
        return $this->delete($this->getAdapter()->quoteInto(
            "configname = ?", $configName));
    }

    public function deleteAndInsert($entities)
    {
        foreach ($entities as $key => $val)
            if (!($val instanceof Domain_Entity_Configuration))
                throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Configuration');
        try {
            $this->getAdapter()->beginTransaction();
            foreach ($entities as $entity) {
                $this->deleteRecord($entity->getConfigName());
                $this->insertRecord($entity);
            }
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
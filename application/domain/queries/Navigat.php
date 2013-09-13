<?php

/**
 * Data access object for table `ws_navigat`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Navigat extends Zend_Db_Table
{
    protected $_name = "ws_navigat";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Navigat();
        $entity->setId($record["id"]);
        $entity->setDescription($record["description"]);
        $entity->setNaviName($record["naviname"]);
        $entity->setLocation($record["location"]);
        $entity->setSequence($record["sequence"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        return $entity;
    }

    public function selectNavigatList()
    {
        $select = $this->select()->order("sequence DESC");
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $navigatList = array();
        foreach ($result as $key => $val)
            $navigatList[$key] = $this->convertRecord($val);
        return $navigatList;
    }

    private function insertRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_Navigat))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Navigat');
        return $this->insert($entity->toArray());
    }

    private function deleteRecord()
    {
        return $this->delete("id > 0");
    }

    public function deleteAndInsert($entities)
    {
        foreach ($entities as $key => $val) {
            if (!($val instanceof Domain_Entity_Navigat))
                throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Navigat');
        }

        try {
            $this->getAdapter()->beginTransaction();
            $this->deleteRecord();
            foreach ($entities as $entity)
                $this->insertRecord($entity);
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}

?>
<?php

/**
 * Data access object for table `ws_contents_extras`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_ContentsExtras extends Zend_Db_Table
{
    protected $_name = 'ws_contents_extras';

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_ContentsExtras();
        $entity->setContId($record["contid"]);
        $entity->setConfig($record["config"]);
        $entity->setParams($record["params"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        return $entity;
    }

    public function selectContentsExtrasList($contId)
    {
        if (!is_numeric($contId))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        $select = $this->select()->where("contid = :contid")->bind(
            array(":contid" => $contId));
        $result = $this->fetchAll($select);
        $extrasList = array();
        foreach ($result as $key => $val)
            $extrasList[$key] = $this->convertRecord($val);
        return $extrasList;
    }

    public function selectContentsExtrasView($contId, $config)
    {
        if (!is_numeric($contId))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        if (!is_string($config))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("contid = :contid AND config = :config")->bind(
            array(":contid" => $contId, ":config" => $config))->limit(1, 0);
        $result = $this->fetchRow($select);
        return $this->convertRecord($result);
    }

    private function insertRecord($entity)
    {
        return $this->insert($entity->toArray());
    }

    public function deleteRecord($contId)
    {
        return $this->delete($this->getAdapter()->quoteInto(
            "contid = ?", $contId));
    }

    public function deleteAndInsert($contId, $entities)
    {
        if (!is_numeric($contId))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        foreach ($entities as $key => $val) {
            if (!($val instanceof Domain_Entity_ContentsExtras))
                throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_ContentsExtras');
        }

        try {
            $this->getAdapter()->beginTransaction();
            $this->deleteRecord($contId);
            foreach ($entities as $entity)
                $this->insertRecord($entity);
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
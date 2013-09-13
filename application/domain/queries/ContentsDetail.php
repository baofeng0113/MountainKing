<?php

/**
 * Data access object for table `ws_contents_detail`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_ContentsDetail extends Zend_Db_Table
{
    protected $_name = 'ws_contents_detail';

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_ContentsDetail();
        $entity->setId($record["id"]);
        $entity->setContId($record["contid"]);
        $entity->setDetail($record["detail"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        return $entity;
    }

    public function selectContentsDetailList($contId)
    {
        if (!is_numeric($contId))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        $select = $this->select()->where("contid = :contid")->bind(
            array(":contid" => $contId))->order("id ASC");
        $result = $this->fetchAll($select);
        $detailList = array();
        foreach ($result as $key => $val)
            $detailList[$key] = $this->convertRecord($val);
        return $detailList;
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
            if (!($val instanceof Domain_Entity_ContentsDetail))
                throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_ContentsDetail');
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
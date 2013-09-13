<?php

/**
 * Data access object for table `ws_digg`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Digg extends Zend_Db_Table
{
    protected $_name = 'ws_digg';

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Digg();
        $entity->setId($record["id"]);
        $entity->setContentId($record["contentid"]);
        $entity->setDigg($record["digg"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        $entity->setUpdatedTime($record["updated_time"]);
        $entity->setUpdatedIp($record["updated_ip"]);
        $entity->setUpdatedUser($record["updated_user"]);
        return $entity;
    }

    public function insertRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_Digg))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Digg');
        return $this->insert($entity->toArray());
    }

    public function selectDiggView($contentId, $createdUser, $createdIp)
    {
        $select = $this->select()->where("contentid = :contentid AND created_user = :created_user
            AND created_ip = :created_ip")->bind(array(":contentid" => $contentId,
            ":created_user" => $createdUser, "created_ip" => $createdIp))->limit(1);
        $result = $select->fetchRow($select);
        if ($result == null) return null;
        return $this->convertRecord($result[0]);
    }

    public function selectDiggNums($contentId, $digg)
    {
        $select = $this->select()->from($this->_name)->columns("COUNT(*) AS counter")->where(
            "contentid = :contentid AND digg = :digg")->bind(array(":contentid" => $contentId,
            ":digg" => $digg));
        $result = $this->fetchRow($select);
        return $result["counter"];
    }
}
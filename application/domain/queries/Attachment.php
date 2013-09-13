<?php

/**
 * Data access object for table `ws_attachment`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Attachment extends Zend_Db_Table
{
    protected $_name = "ws_attachment";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Attachment();
        $entity->setId($record["id"]);
        $entity->setFileName($record["filename"]);
        $entity->setFileSize($record["filesize"]);
        $entity->setFileType($record["filetype"]);
        $entity->setSequence($record["sequence"]);
        $entity->setDescription($record["description"]);
        $entity->setServerPath($record["server_path"]);
        $entity->setServerHost($record["server_host"]);
        $entity->setServerURI1($record["server_uri1"]);
        $entity->setServerURI2($record["server_uri2"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        return $entity;
    }

    public function insertRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_Attachment))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Attachment');
        return $this->insert($entity->toArray());
    }

    public function selectAttachmentList($search, $offset, $limit)
    {
        if (!($search instanceof Domain_Search_Attachment))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Attachment');
        $select = $this->parseAttachmentSearchObject($this->select(), $search)->order(
            "created_time DESC")->limit($limit, $offset);
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $attachmentList = array();
        foreach ($result as $key => $val)
            $attachmentList[$key] = $this->convertRecord($val);
        return $attachmentList;
    }

    public function selectAttachmentNums($search)
    {
        if (!($search instanceof Domain_Search_Attachment))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Attachment');
        $select = $this->parseAttachmentSearchObject($this->select()->from(
            $this->_name)->columns(array('COUNT(*) AS counter')), $search);
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function selectAttachmentView($id)
    {
        if (!is_numeric($id))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        $select = $this->select()->where("id = :id")->bind(
            array(":id" => $id))->limit(1, 0);
        $result = $this->fetchRow($select);
        return $this->convertRecord($result);
    }

    public function deleteRecord($id)
    {
        if (!is_numeric($id))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        return $this->delete($this->getAdapter()->quoteInto(
            "id = ?", $id));
    }

    private function parseAttachmentSearchObject($select, $search)
    {
        $query = $value = array();

        if ($search->getCreatedUser() !== null) {
            $query[] = "created_user = :created_user";
            $value[":created_user"] = $search->getCreatedUser();
        }
        if ($search->getCreatedIp() !== null) {
            $query[] = "created_ip = :created_ip";
            $value[":created_ip"] = $search->getCreatedIp();
        }
        if ($search->getCreatedTime1() !== null) {
            $query[] = "created_time >= :created_time1";
            $value[":created_time1"] = $search->getCreatedTime1();
        }
        if ($search->getCreatedTime2() !== null) {
            $query[] = "created_time <= :created_time2";
            $value[":created_time2"] = $search->getCreatedTime2();
        }
        if (sizeof($query) > 0) {
            $select->where(implode(" AND ", $query))->bind($value);
        }
        return $select;
    }
}
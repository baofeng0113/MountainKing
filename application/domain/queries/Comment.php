<?php

/**
 * Data access object for table `ws_comment`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Comment extends Zend_Db_Table
{
    protected $_name = 'ws_comment';

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Comment();
        $entity->setId($record["id"]);
        $entity->setContentId($record["contentid"]);
        $entity->setComment($record["comment"]);
        $entity->setPublish($record["publish"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        $entity->setUpdatedTime($record["updated_time"]);
        $entity->setUpdatedIp($record["updated_ip"]);
        $entity->setUpdatedUser($record["updated_user"]);
        return $entity;
    }

    public function deleteRecord($id)
    {
        if (!is_numeric($id))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        return $this->delete($this->getAdapter()->quoteInto(
            "id = ?", $id));
    }

    public function insertRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_Comment))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Comment');
        return $this->insert($entity->toArray());
    }

    public function updatePublish($entity)
    {
        if (!($entity instanceof Domain_Entity_Comment))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Comment');
        $params = $entity->toArray();
        return $this->update(array("publish" => $params["publish"],
                "updated_time" => $params["updated_time"],
                "updated_ip" => $params["updated_ip"],
                "updated_user" => $params["updated_user"]),
            $this->getAdapter()->quoteInto("id = ?", $params["id"]));
    }

    public function selectCommentList($search, $offset, $limit)
    {
        if (!($search instanceof Domain_Search_Comment))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Comment');
        $select = $this->parseCommentSearchObject($this->select(), $search)->order(
            "created_time DESC")->limit($limit, $offset);
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $commentList = array();
        foreach ($result as $key => $val)
            $commentList[$key] = $this->convertRecord($val);
        return $commentList;
    }

    public function selectCommentNums($search)
    {
        if (!($search instanceof Domain_Search_Comment))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Comment');
        $select = $this->parseCommentSearchObject($this->select()->from(
            $this->_name)->columns(array('COUNT(*) AS counter')), $search);
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    private function parseCommentSearchObject($select, $search)
    {
        $query = $value = array();

        if ($search->getContentId() !== null) {
            $query[] = "contentid = :contentid";
            $value[":contentid"] = $search->getContentId();
        }
        if ($search->getPublish() !== null) {
            $query[] = "publish = :publish";
            $value[":publish"] = $search->getPublish();
        }
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
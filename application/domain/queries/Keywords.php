<?php

/**
 * Data access object for table `ws_keywords`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Keywords extends Zend_Db_Table
{
    protected $_name = "ws_keywords";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Keywords();
        $entity->setKeyword($record["keyword"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        return $entity;
    }

    public function selectKeywordsNums($search)
    {
        if (!($search instanceof Domain_Search_Keywords))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Keywords');
        $select = $this->parseKeywordsSearchObject($this->select()->from(
            $this->_name)->columns(array('COUNT(*) AS counter')), $search);
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function selectKeywordsList($search, $offset, $limit)
    {
        if (!($search instanceof Domain_Search_Keywords))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Keywords');
        $select = $this->parseKeywordsSearchObject($this->select(), $search)->order(
            "created_time asc")->limit($limit, $offset);
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $keywordsList = array();
        foreach ($result as $key => $val)
            $keywordsList[$key] = $this->convertRecord($val);
        return $keywordsList;
    }

    public function deleteRecord($keyword)
    {
        if (!is_string($keyword))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        return $this->delete($this->getAdapter()->quoteInto(
            "keyword = ?", $keyword));
    }

    public function insertRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_Keywords))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Keywords');
        try {
            $keyword = $entity->getKeyword();
            $this->getAdapter()->beginTransaction();
            $this->deleteRecord($keyword);
            $this->insert($entity->toArray());
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    private function parseKeywordsSearchObject($select, $search)
    {
        $query = $value = array();

        if ($search->getKeyword() !== null) {
            $query[] = "keyword LIKE :keyword";
            $value[":keyword"] = '%' . $search->getKeyword() . '%';
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
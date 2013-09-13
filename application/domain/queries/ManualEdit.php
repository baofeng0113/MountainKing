<?php

/**
 * Data access object for table `ws_manualedit`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_ManualEdit extends Zend_Db_Table
{
    protected $_name = "ws_manualedit";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_ManualEdit();
        $entity->setCode($record["code"]);
        $entity->setName($record["name"]);
        $entity->setHtml($record["html"]);
        $entity->setDescription($record["description"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        $entity->setUpdatedTime($record["updated_time"]);
        $entity->setUpdatedIp($record["updated_ip"]);
        $entity->setUpdatedUser($record["updated_user"]);
        return $entity;
    }

    public function selectManualEditView($code)
    {
        if (!is_string($code))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("code = :code")->bind(
            array(":code" => $code))->limit(1, 0);
        $result = $this->fetchRow($select);
        return $this->convertRecord($result);
    }

    public function selectManualEditNums($search)
    {
        if (!($search instanceof Domain_Search_ManualEdit))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_ManualEdit');
        $select = $this->parseManualEditSearchObject($this->select()->from(
            $this->_name)->columns(array('COUNT(*) AS counter')), $search);
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function selectManualEditList($search, $offset, $limit)
    {
        if (!($search instanceof Domain_Search_ManualEdit))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_ManualEdit');
        $select = $this->parseManualEditSearchObject($this->select(), $search)->order(
            "code asc")->limit($limit, $offset);
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $manualEditList = array();
        foreach ($result as $key => $val)
            $manualEditList[$key] = $this->convertRecord($val);
        return $manualEditList;
    }

    public function deleteRecord($code)
    {
        if (!is_string($code))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        return $this->delete($this->getAdapter()->quoteInto(
            "code = ?", $code));
    }

    public function insertRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_ManualEdit))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_ManualEdit');
        return $this->insert($entity->toArray());
    }

    public function updateRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_ManualEdit))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_ManualEdit');

        try {
            $this->getAdapter()->beginTransaction();
            $this->deleteRecord($entity->getCode());
            $this->insertRecord($entity);
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    private function parseManualEditSearchObject($select, $search)
    {
        $query = $value = array();

        if ($search->getCode() !== null) {
            $query[] = "code LIKE :code";
            $value[":code"] = "%" . $search->getCode() . "%";
        }
        if ($search->getName() !== null) {
            $query[] = "name LIKE :name";
            $value[":name"] = "%" . $search->getName() . "%";
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
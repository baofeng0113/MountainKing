<?php

/**
 * Data access object for table `cp_admin_loginlog`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_AdminLoginLog extends Zend_Db_Table
{
    protected $_name = "cp_admin_loginlog";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_AdminLoginLog();
        $entity->setLoginResult($record["loginresult"]);
        $entity->setDescription($record["description"]);
        $entity->setId($record["id"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        return $entity;
    }

    public function selectAdminLoginLogList($search, $offset, $limit)
    {
        if (!($search instanceof Domain_Search_AdminLoginLog))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_AdminLoginLog');
        $select = $this->parseAdminLoginLogSearchObject($this->select(), $search)->order(
            "created_time DESC")->limit($limit, $offset);
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $loginLogList = array();
        foreach ($result as $key => $val)
            $loginLogList[$key] = $this->convertRecord($val);
        return $loginLogList;
    }

    public function selectAdminLoginLogNums($search)
    {
        if (!($search instanceof Domain_Search_AdminLoginLog))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_AdminLoginLog');
        $select = $this->parseAdminLoginLogSearchObject($this->select()->from(
            $this->_name)->columns(array('COUNT(*) AS counter')), $search);
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function insertRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_AdminLoginLog))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_AdminLoginLog');
        return $this->insert($entity->toArray());
    }

    private function parseAdminLoginLogSearchObject($select, $search)
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
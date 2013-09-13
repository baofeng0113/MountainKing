<?php

/**
 * Data access object for table `cp_admin_accredit`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_AdminAccredit extends Zend_Db_Table
{
    protected $_name = "cp_admin_accredit";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_AdminAccredit();
        $entity->setAuthoriz($record["authoriz"]);
        $entity->setUsername($record["username"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        return $entity;
    }

    public function selectAdminAccreditList($username)
    {
        if (!is_string($username))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("username = :username")->bind(
            array(":username" => $username));
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $adminAccreditList = array();
        foreach ($result as $key => $val)
            $adminAccreditList[$key] = $this->convertRecord($val);
        return $adminAccreditList;
    }

    public function selectAdminAccreditView($username, $authoriz)
    {
        if (!is_string($username) || !is_string($authoriz))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("username = :username AND
			authoriz = :authoriz")->bind(array(
            ":username" => $username, "authoriz" => $authoriz));
        $result = $this->fetchRow($select);
        return $result == null ? null : $this->convertRecord($result);
    }

    private function insertRecord($entity)
    {
        return $this->insert($entity->toArray());
    }

    private function deleteRecord($username)
    {
        return $this->delete($this->getAdapter()->quoteInto(
            "username = ?", $username));
    }

    public function deleteAndInsert($username, $entities)
    {
        if (!is_string($username))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        foreach ($entities as $key => $val)
            if (!($val instanceof Domain_Entity_AdminAccredit))
                throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_AdminAccredit');
        try {
            $this->getAdapter()->beginTransaction();
            $this->deleteRecord($username);
            foreach ($entities as $entity)
                $this->insertRecord($entity);
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
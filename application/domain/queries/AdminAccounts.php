<?php

/**
 * Data access object for table `cp_admin_accounts`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_AdminAccounts extends Zend_Db_Table
{
    protected $_name = "cp_admin_accounts";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_AdminAccounts();
        $entity->setDisabled($record["disabled"]);
        $entity->setUsername($record["username"]);
        $entity->setPassword($record["password"]);
        $entity->setCryptKey($record["cryptkey"]);
        $entity->setTrueName($record["truename"]);
        $entity->setMailBox($record["mailbox"]);
        $entity->setMobCode($record["mobcode"]);
        $entity->setTelCode($record["telcode"]);
        $entity->setFaxCode($record["faxcode"]);
        $entity->setDescription($record["description"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        $entity->setUpdatedTime($record["updated_time"]);
        $entity->setUpdatedIp($record["updated_ip"]);
        $entity->setUpdatedUser($record["updated_user"]);
        return $entity;
    }

    public function deleteRecord($username)
    {
        if (!is_string($username))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        return $this->delete($this->getAdapter()->quoteInto(
            "username = ?", $username));
    }

    public function insertRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_AdminAccounts))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_AdminAccounts');
        return $this->insert($entity->toArray());
    }

    public function updateRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_AdminAccounts))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_AdminAccounts');
        $params = $entity->toArray();
        return $this->update(array("truename" => $params["truename"],
                "mailbox" => $params["mailbox"],
                "mobcode" => $params["mobcode"],
                "telcode" => $params["telcode"],
                "faxcode" => $params["faxcode"],
                "description" => $params["description"],
                "disabled" => $params["disabled"],
                "updated_time" => $params["updated_time"],
                "updated_ip" => $params["updated_ip"],
                "updated_user" => $params["updated_user"]),
            $this->getAdapter()->quoteInto("username = ?", $params["username"]));
    }

    public function selectAdminAccountView($username)
    {
        if (!is_string($username))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("username = :username")->bind(
            array(":username" => $username))->order("username ASC")->limit(1, 0);
        $result = $this->fetchRow($select);
        return $this->convertRecord($result);
    }

    public function selectAdminAccountList($search, $offset, $limit)
    {
        if (!($search instanceof Domain_Search_AdminAccounts))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_AdminAccounts');
        $select = $this->parseAdminAccountSearchObject($this->select(), $search)->order(
            "created_time DESC")->limit($limit, $offset);
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $accountsList = array();
        foreach ($result as $key => $val)
            $accountsList[$key] = $this->convertRecord($val);
        return $accountsList;
    }

    public function selectAdminAccountNums($search)
    {
        if (!($search instanceof Domain_Search_AdminAccounts))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_AdminAccounts');
        $select = $this->parseAdminAccountSearchObject($this->select()->from(
            $this->_name)->columns(array('COUNT(*) AS counter')), $search);
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function updatePassword($entity)
    {
        if (!($entity instanceof Domain_Entity_AdminAccounts))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_AdminAccounts');
        $params = $entity->toArray();
        return $this->update(array("password" => $params["password"],
                "updated_time" => $params["updated_time"],
                "updated_ip" => $params["updated_ip"],
                "updated_user" => $params["updated_user"]),
            $this->getAdapter()->quoteInto("username = ?", $params["username"]));
    }

    private function parseAdminAccountSearchObject($select, $search)
    {
        $query = $value = array();

        if ($search->getUsername() !== null) {
            $query[] = "username LIKE :username";
            $value[":username"] = '%' . $search->getUsername() . '%';
        }
        if ($search->getTrueName() !== null) {
            $query[] = "truename LIKE :truename";
            $value[":truename"] = '%' . $search->getTrueName() . '%';
        }
        if ($search->getDisabled() !== null) {
            $query[] = "disabled = :disabled";
            $value[":disabled"] = $search->getDisabled();
        }
        if ($search->getMailBox() !== null) {
            $query[] = "mailbox = :mailbox";
            $value[":mailbox"] = $search->getMailBox();
        }
        if ($search->getMobCode() !== null) {
            $query[] = "mobcode = :mobcode";
            $value[":mobcode"] = $search->getMobCode();
        }
        if ($search->getTelCode() !== null) {
            $query[] = "telcode = :telcode";
            $value[":telcode"] = $search->getTelCode();
        }
        if ($search->getFaxCode() !== null) {
            $query[] = "faxcode = :faxcode";
            $value[":faxcode"] = $search->getFaxCode();
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
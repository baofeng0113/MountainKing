<?php

/**
 * Data access object for table `ws_links`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Links extends Zend_Db_Table
{
    protected $_name = "ws_links";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Links();
        $entity->setId($record["id"]);
        $entity->setLinkName($record["linkname"]);
        $entity->setLinkLogo($record["linklogo"]);
        $entity->setLocation($record["location"]);
        $entity->setSequence($record["sequence"]);
        $entity->setDescription($record["description"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        $entity->setUpdatedTime($record["updated_time"]);
        $entity->setUpdatedIp($record["updated_ip"]);
        $entity->setUpdatedUser($record["updated_user"]);
        return $entity;
    }

    public function selectLinksView($id)
    {
        if (!is_numeric($id))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        $select = $this->select()->where("id = :id")->bind(
            array(":id" => $id))->limit(1, 0);
        $result = $this->fetchRow($select);
        return $this->convertRecord($result);
    }

    public function selectLinksNums($search)
    {
        if (!($search instanceof Domain_Search_Links))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Links');
        $select = $this->parseLinksSearchObject($this->select()->from(
            $this->_name)->columns(array('COUNT(*) AS counter')), $search);
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function selectLinksList($search, $offset, $limit)
    {
        if (!($search instanceof Domain_Search_Links))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Links');
        $select = $this->parseLinksSearchObject($this->select(), $search)->order(
            "sequence asc")->limit($limit, $offset);
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $linksList = array();
        foreach ($result as $key => $val)
            $linksList[$key] = $this->convertRecord($val);
        return $linksList;
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
        if (!($entity instanceof Domain_Entity_Links))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Links');
        return $this->insert($entity->toArray());
    }

    public function updateRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_Links))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Links');
        $params = $entity->toArray();
        return $this->update(array("linkname" => $params["linkname"],
                "linklogo" => $params["linklogo"],
                "location" => $params["location"],
                "sequence" => $params["sequence"],
                "description" => $params["description"],
                "updated_time" => $params["updated_time"],
                "updated_ip" => $params["updated_ip"],
                "updated_user" => $params["updated_user"]),
            $this->getAdapter()->quoteInto("id = ?", $params["id"]));
    }

    private function parseLinksSearchObject($select, $search)
    {
        $query = $value = array();

        if ($search->getLinkName() !== null) {
            $query[] = "linkname LIKE :linkname";
            $value[":linkname"] = '%' . $search->getLinkName() . '%';
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
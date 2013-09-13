<?php

/**
 * Data access object for table `ws_contents`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Contents extends Zend_Db_Table
{
    protected $_name = 'ws_contents';

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Contents();
        $entity->setId($record["id"]);
        $entity->setViewType($record["viewtype"]);
        $entity->setKeywords($record["keywords"]);
        $entity->setDescription($record["description"]);
        $entity->setCategory($record["category"]);
        $entity->setDisabled($record["disabled"]);
        $entity->setSequence($record["sequence"]);
        $entity->setPublish($record["publish"]);
        $entity->setSubject($record["subject"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        $entity->setUpdatedTime($record["updated_time"]);
        $entity->setUpdatedIp($record["updated_ip"]);
        $entity->setUpdatedUser($record["updated_user"]);
        return $entity;
    }

    public function selectContentsNums($search)
    {
        if (!($search instanceof Domain_Search_Contents))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Contents');
        $select = $this->parseContentsSearchObject($this->select()->from(
            $this->_name)->columns(array('COUNT(*) AS counter')), $search);
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function selectContentsList($search, $offset, $limit)
    {
        if (!($search instanceof Domain_Search_Contents))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Contents');
        $select = $this->parseContentsSearchObject($this->select(), $search)->order(
            array("sequence DESC", "created_time DESC"))->limit($limit, $offset);
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $contentsList = array();
        foreach ($result as $key => $val)
            $contentsList[$key] = $this->convertRecord($val);
        return $contentsList;
    }

    public function selectContentsView($id)
    {
        if (!is_numeric($id))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        $select = $this->select()->where("id = :id")->bind(
            array(":id" => $id))->limit(1, 0);
        $result = $this->fetchRow($select);
        return $this->convertRecord($result);
    }

    public function insertRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_Contents))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Contents');
        return $this->insert($entity->toArray());
    }

    public function deleteRecord($id)
    {
        if (!is_numeric($id))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        return $this->delete($this->getAdapter()->quoteInto(
            "id = ?", $id));
    }

    public function updatePublish($entity)
    {
        if (!($entity instanceof Domain_Entity_Contents))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Contents');
        $params = $entity->toArray();
        return $this->update(array("publish" => $params["publish"],
                "updated_time" => $params["updated_time"],
                "updated_ip" => $params["updated_ip"],
                "updated_user" => $params["updated_user"]),
            $this->getAdapter()->quoteInto("id = ?", $params["id"]));
    }

    private function parseContentsSearchObject($select, $search)
    {
        $query = $value = array();

        if ($search->getCategory() !== null) {
            $query[] = "category = :category";
            $value[":category"] = $search->getCategory();
        }
        if ($search->getContents() !== null) {
            $query[] = "(keywords LIKE :contents OR description LIKE :contents OR subject LIKE :contents)";
            $value[":contents"] = '%' . $search->getContents() . '%';
        }
        if ($search->getViewType() !== null) {
            $query[] = "viewtype = :viewtype";
            $value[":viewtype"] = $search->getViewType();
        }
        if ($search->getDisabled() !== null) {
            $query[] = "disabled = :disabled";
            $value[":disabled"] = $search->getDisabled();
        }
        if ($search->getPublish() !== null) {
            $query[] = "publish = :publish";
            $value[":publish"] = $search->getPublish();
        }
        if ($search->getCreatedUser() !== null) {
            $query[] = "created_user = :created_user";
            $value[":created_user"] = $search->getCreatedUser();
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
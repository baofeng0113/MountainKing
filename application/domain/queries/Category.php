<?php

/**
 * Data access object for table `ws_category`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Category extends Zend_Db_Table
{
    protected $_name = "ws_category";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Category();
        $entity->setId($record["id"]);
        $entity->setDescription($record["description"]);
        $entity->setCateName($record["catename"]);
        $entity->setCateCode($record["catecode"]);
        $entity->setCateLogo($record["catelogo"]);
        $entity->setCateType($record["catetype"]);
        $entity->setDisabled($record["disabled"]);
        $entity->setTemplate($record["template"]);
        $entity->setSequence($record["sequence"]);
        $entity->setKeywords($record["keywords"]);
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
        if (!($entity instanceof Domain_Entity_Category))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Category');
        return $this->insert($entity->toArray());
    }

    public function updateRecord($entity)
    {
        if (!($entity instanceof Domain_Entity_Category))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_Category');
        $params = $entity->toArray();
        return $this->update(array("catename" => $params["catename"],
                "catelogo" => $params["catelogo"],
                "catetype" => $params["catetype"],
                "template" => $params["template"],
                "keywords" => $params["keywords"],
                "sequence" => $params["sequence"],
                "description" => $params["description"],
                "disabled" => $params["disabled"],
                "updated_time" => $params["updated_time"],
                "updated_ip" => $params["updated_ip"],
                "updated_user" => $params["updated_user"]),
            $this->getAdapter()->quoteInto("id = ?", $params["id"]));
    }

    public function selectCategoryList($search, $offset, $limit)
    {
        if (!($search instanceof Domain_Search_Category))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Category');
        $select = $this->parseCategorySearchObject($this->select(), $search)->order(
            "sequence ASC")->limit($limit, $offset);
        $result = $this->fetchAll($select);
        if ($result == null) return null;
        $categoryList = array();
        foreach ($result as $key => $val)
            $categoryList[$key] = $this->convertRecord($val);
        return $categoryList;
    }

    public function selectCategoryNums($search)
    {
        if (!($search instanceof Domain_Search_Category))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Search_Category');
        $select = $this->parseCategorySearchObject($this->select()->from(
            $this->_name)->columns(array('COUNT(*) AS counter')), $search);
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function selectCategoryViewById($id)
    {
        if (!is_numeric($id))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        $select = $this->select()->where("id = :id")->bind(
            array(":id" => $id))->limit(1, 0);
        $result = $this->fetchRow($select);
        return $this->convertRecord($result);
    }

    public function selectCategoryViewByCode($cateCode)
    {
        if (!is_string($cateCode))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("catecode = :catecode")->bind(
            array(":catecode" => $cateCode))->limit(1, 0);
        $result = $this->fetchRow($select);
        return $this->convertRecord($result);
    }

    public function selectCategoryCodeExist($cateCode)
    {
        if (!is_string($cateCode))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->from($this->_name)->columns(
            'COUNT(*) AS counter')->where("catecode = :catecode")->bind(
            array(":catecode" => $cateCode));
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function selectCategoryNameExist($cateName, $cateId)
    {
        if (!is_string($cateName))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        if ($cateId && !is_numeric($cateId))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        $select = $cateId ? $this->select()->from($this->_name)->columns(
            'COUNT(*) AS counter')->where("catename = :catename AND id <> :id")->bind(
            array(":catename" => $cateName, ":id" => $cateId)) : $this->select()->from(
            $this->_name)->columns('COUNT(*) AS counter')->where(
            "catename = :catename")->bind(array(":catename" => $cateName));
        $result = $this->fetchRow($select);
        return $result["counter"];
    }

    public function selectNextCategoryMaxCode($cateCode)
    {
        if (!is_string($cateCode) || (strlen($cateCode) % 3) !== 0)
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string or invalid');
        $select = $this->select()->from($this->_name)->columns(
            'MAX(`catecode`) AS maxcode')->where("LENGTH(`catecode`) = :strlen AND catecode LIKE :catecode"
        )->bind(array(":strlen" => strlen($cateCode) + 3, ":catecode" => $cateCode . "%"));
        $result = $this->fetchRow($select);
        return $result["maxcode"];
    }

    private function parseCategorySearchObject($select, $search)
    {
        $query = $value = array();

        if ($search->getCateName() !== null) {
            $query[] = "catename LIKE :catename";
            $value[":catename"] = '%' . $search->getCateName() . '%';
        }
        if ($search->getCateCode() !== null) {
            $query[] = "catecode LIKE :catecode1 AND catecode <> :catecode2";
            $value[":catecode1"] = $search->getCateCode() . '%';
            $value[":catecode2"] = $search->getCateCode();
        }
        if ($search->getCateType() !== null) {
            $query[] = "catetype = :catetype";
            $value[":catetype"] = $search->getCateType();
        }
        if ($search->getDisabled() !== null) {
            $query[] = "disabled = :disabled";
            $value[":disabled"] = $search->getDisabled();
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
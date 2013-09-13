<?php

/**
 * Data access object for table `ws_template`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_Template extends Zend_Db_Table
{
    protected $_name = "ws_template";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_Template();
        $entity->setId($record["id"]);
        $entity->setDescription($record["description"]);
        $entity->setThemeName($record["themename"]);
        $entity->setDirectory($record["directory"]);
        $entity->setCopyright($record["copyright"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        $entity->setUpdatedTime($record["updated_time"]);
        $entity->setUpdatedIp($record["updated_ip"]);
        $entity->setUpdatedUser($record["updated_user"]);
        return $entity;
    }

    public function selectTemplateList()
    {
        $result = $this->fetchAll();
        if ($result == null) return null;
        $templateList = array();
        foreach ($result as $key => $val)
            $templateList[$key] = $this->convertRecord($val);
        return $templateList;
    }

    public function selectTemplateView($directory)
    {
        if (!is_string($directory))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("directory = :directory")->bind(
            array(":directory" => $directory))->limit(1, 0);
        $result = $this->fetchRow($select);
        return $this->convertRecord($result);
    }

    public function selectDirectoryExist($directory)
    {
        if (!is_string($directory))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->from($this->_name)->columns(
            'COUNT(*) AS counter')->where("directory = :directory")->bind(
            array(":directory" => $directory));
        $result = $this->fetchRow($select);
        return $result["counter"];
    }
}
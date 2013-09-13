<?php

/**
 * Data access object for table `cp_category_editor`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_CategoryEditor extends Zend_Db_Table
{
    protected $_name = "cp_category_editor";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_CategoryEditor();
        $entity->setCateId($record["cateid"]);
        $entity->setEditor($record["editor"]);
        $entity->setCreatedTime($record["created_time"]);
        $entity->setCreatedIp($record["created_ip"]);
        $entity->setCreatedUser($record["created_user"]);
        return $entity;
    }

    public function selectCategoryEditorList($cateId)
    {
        if (!is_numeric($cateId))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        $select = $this->select()->where("cateid = :cateid")->bind(
            array(":cateid" => $cateId));
        $result = $this->fetchAll($select);
        $editorList = array();
        foreach ($result as $key => $val)
            $editorList[$key] = $this->convertRecord($val);
        return $editorList;
    }

    public function selectEditorCategoryList($editor)
    {
        if (!is_string($editor))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("editor = :editor")->bind(
            array(":editor" => $editor));
        $result = $this->fetchAll($select);
        $editorList = array();
        foreach ($result as $key => $val)
            $editorList[$key] = $this->convertRecord($val);
        return $editorList;
    }

    private function insertRecord($entity)
    {
        return $this->insert($entity->toArray());
    }

    public function deleteRecord($cateId)
    {
        return $this->delete($this->getAdapter()->quoteInto(
            "cateid = ?", $cateId));
    }

    public function deleteAndInsert($cateId, $entities)
    {
        if (!is_numeric($cateId))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a numeric');
        foreach ($entities as $key => $val) {
            if (!($val instanceof Domain_Entity_CategoryEditor))
                throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not instanceof Domain_Entity_CategoryEditor');
        }

        try {
            $this->getAdapter()->beginTransaction();
            $this->deleteRecord($cateId);
            foreach ($entities as $entity)
                $this->insertRecord($entity);
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $this->getAdapter()->rollBack();
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
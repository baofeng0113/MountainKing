<?php

/**
 * Data access object for table `cp_linkauthed`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Query
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Query_LinkAuthed extends Zend_Db_Table
{
    protected $_name = "cp_linkauthed";

    private function convertRecord($record)
    {
        if ($record == null || empty($record))
            return null;
        $entity = new Domain_Entity_LinkAuthed();
        $entity->setLink($record["link"]);
        $entity->setAuthoriz($record["authoriz"]);
        return $entity;
    }

    public function selectLinkAuthedView($link)
    {
        if (!is_string($link))
            throw new Exception('PARAMETER[ERROR]: ' . __METHOD__ . ': The parameter is not a string');
        $select = $this->select()->where("link = :link")->bind(array(":link" => $link));
        $result = $this->fetchRow($select);
        return $result == null ? null : $this->convertRecord($result);
    }
}
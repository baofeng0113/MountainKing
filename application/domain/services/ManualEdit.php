<?php

/**
 * Implementation class of service layer
 *
 * @license Apache License 2.0
 *
 * @package Domain_Service
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Service_ManualEdit
{
    private $domainQueryManualEdit = null;

    public function Domain_Service_ManualEdit()
    {
        $this->domainQueryManualEdit = new Domain_Query_ManualEdit();
    }

    public function getManualEditList($search, $offset = 0, $limit = 20)
    {
        return $this->domainQueryManualEdit->selectManualEditList($search, $offset, $limit);
    }

    public function getManualEditNums($search)
    {
        return $this->domainQueryManualEdit->selectManualEditNums($search);
    }

    public function getManualEditView($code)
    {
        return $this->domainQueryManualEdit->selectManualEditView($code);
    }

    public function addManualEdit($entity)
    {
        return $this->domainQueryManualEdit->insertRecord($entity);
    }

    public function delManualEdit($code)
    {
        return $this->domainQueryManualEdit->deleteRecord($code);
    }

    public function modManualEdit($entity)
    {
        return $this->domainQueryManualEdit->updateRecord($entity);
    }
}
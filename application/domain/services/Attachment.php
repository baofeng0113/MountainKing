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
class Domain_Service_Attachment
{
    private $domainQueryAttachment = null;

    public function Domain_Service_Attachment()
    {
        $this->domainQueryAttachment = new Domain_Query_Attachment();
    }

    public function getAttachmentList($search, $offset = 0, $limit = 30)
    {
        return $this->domainQueryAttachment->selectAttachmentList(
            $search, $offset, $limit);
    }

    public function getAttachmentNums($search)
    {
        return $this->domainQueryAttachment->selectAttachmentNums($search);
    }

    public function getAttachmentView($id)
    {
        return $this->domainQueryAttachment->selectAttachmentView($id);
    }

    public function delAttachment($id)
    {
        return $this->domainQueryAttachment->deleteRecord($id);
    }

    public function addAttachment($entity)
    {
        return $this->domainQueryAttachment->insertRecord($entity);
    }
}
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
class Domain_Service_Comment
{
    private $domainQueryComment = null;

    public function Domain_Service_Comment()
    {
        $this->domainQueryComment = new Domain_Query_Comment();
    }

    public function getCommentList($search, $offset = 0, $limit = 20)
    {
        return $this->domainQueryComment->selectCommentList($search, $offset, $limit);
    }

    public function getCommentNums($search)
    {
        return $this->domainQueryComment->selectCommentNums($search);
    }

    public function addComment($entity)
    {
        return $this->domainQueryComment->insertRecord($entity);
    }

    public function delComment($id)
    {
        return $this->domainQueryComment->deleteRecord($id);
    }

    public function modPublish($entity)
    {
        return $this->domainQueryComment->updatePublish($entity);
    }
}
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
class Domain_Service_Links
{
    private $domainQueryLinks = null;

    public function Domain_Service_Links()
    {
        $this->domainQueryLinks = new Domain_Query_Links();
    }

    public function addLinks($entity)
    {
        return $this->domainQueryLinks->insertRecord($entity);
    }

    public function modLinks($entity)
    {
        return $this->domainQueryLinks->updateRecord($entity);
    }

    public function delLinks($id)
    {
        return $this->domainQueryLinks->deleteRecord($id);
    }

    public function getLinksView($id)
    {
        return $this->domainQueryLinks->selectLinksView($id);
    }

    public function getLinksList($search, $offset = 0, $limit = 20)
    {
        return $this->domainQueryLinks->selectLinksList($search, $offset, $limit);
    }

    public function getLinksNums($search)
    {
        return $this->domainQueryLinks->selectLinksNums($search);
    }
}
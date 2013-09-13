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
class Domain_Service_Keywords
{
    private $domainQueryKeywords = null;

    public function Domain_Service_Keywords()
    {
        $this->domainQueryKeywords = new Domain_Query_Keywords();
    }

    public function getKeywordsList($search, $offset = 0, $limit = 20)
    {
        return $this->domainQueryKeywords->selectKeywordsList($search, $offset, $limit);
    }

    public function getKeywordsNums($search)
    {
        return $this->domainQueryKeywords->selectKeywordsNums($search);
    }

    public function delKeywords($keyword)
    {
        return $this->domainQueryKeywords->deleteRecord($keyword);
    }

    public function addKeywords($keyword)
    {
        return $this->domainQueryKeywords->insertRecord($keyword);
    }
}
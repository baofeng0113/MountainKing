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
class Domain_Service_Template
{
    private $domainQueryTemplate = null;

    public function Domain_Service_Template()
    {
        $this->domainQueryTemplate = new Domain_Query_Template();
    }

    public function getTemplateList()
    {
        return $this->domainQueryTemplate->selectTemplateList();
    }

    public function getTemplateView($directory)
    {
        return $this->domainQueryTemplate->selectTemplateView(
            $directory);
    }

    public function isDirectoryExist($directory)
    {
        return $this->domainQueryTemplate->selectDirectoryExist(
            $directory) == 0 ? false : true;
    }
}
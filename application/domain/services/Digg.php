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
class Domain_Service_Digg
{
    private $domainQueryDigg = null;

    public function Domain_Service_Digg()
    {
        $this->domainQueryDigg = new Domain_Query_Digg();
    }

    public function getDiggView($contentId, $createdUser, $createdIp)
    {
        return $this->domainQueryDigg->selectDiggView($contentId, $createdUser, $createdIp);
    }

    public function getDiggNums($contentId, $createdUser)
    {
        return $this->domainQueryDigg->selectDiggNums($contentId, $createdUser);
    }

    public function addDigg($entity)
    {
        return $this->domainQueryDigg->insertRecord($entity);
    }
}
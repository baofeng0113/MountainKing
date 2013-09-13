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
class Domain_Service_Navigat
{
    private $domainQueryNavigat = null;

    public function Domain_Service_Navigat()
    {
        $this->domainQueryNavigat = new Domain_Query_Navigat();
    }

    public function getNavigatList()
    {
        return $this->domainQueryNavigat->selectNavigatList();
    }

    public function deleteAndInsert($entities)
    {
        return $this->domainQueryNavigat->deleteAndInsert($entities);
    }
}

?>
<?php

/**
 * Data Object for table `cp_linkauthed`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_LinkAuthed
{
    private $authoriz = null;

    private $link = null;

    public function getAuthoriz()
    {
        return $this->authoriz;
    }

    public function setAuthoriz($authoriz)
    {
        $this->authoriz = $authoriz;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function toArray()
    {
        $object2Array = array();
        if ($this->authoriz !== null)
            $object2Array['authoriz'] = $this->authoriz;
        if ($this->link !== null)
            $object2Array['link'] = $this->link;
        return $object2Array;
    }
}
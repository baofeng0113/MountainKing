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
class Domain_Service_Contents
{
    private $domainQueryContentsDetail = null;

    private $domainQueryContents = null;

    private $domainQueryContentsExtras = null;

    private $detailContextPool = array();

    public function Domain_Service_Contents()
    {
        $this->domainQueryContentsExtras = new Domain_Query_ContentsExtras();

        $this->domainQueryContents = new Domain_Query_Contents();

        $this->domainQueryContentsDetail = new Domain_Query_ContentsDetail();

        $this->setDetailContext(0, $this->parseDetailContext(
            APPLICATION_PATH . '/context/content-detail-0.xml'));
        $this->setDetailContext(1, $this->parseDetailContext(
            APPLICATION_PATH . '/context/content-detail-1.xml'));
        $this->setDetailContext(2, $this->parseDetailContext(
            APPLICATION_PATH . '/context/content-detail-2.xml'));
        $this->setDetailContext(3, $this->parseDetailContext(
            APPLICATION_PATH . '/context/content-detail-3.xml'));
        $this->setDetailContext(4, $this->parseDetailContext(
            APPLICATION_PATH . '/context/content-detail-4.xml'));
        $this->setDetailContext(5, $this->parseDetailContext(
            APPLICATION_PATH . '/context/content-detail-5.xml'));
    }

    public function getContentsList($search, $offset = 0, $limit = 20)
    {
        return $this->domainQueryContents->selectContentsList($search, $offset, $limit);
    }

    public function getContentsNums($search)
    {
        return $this->domainQueryContents->selectContentsNums($search);
    }

    public function getContentsView($id)
    {
        return $this->domainQueryContents->selectContentsView($id);
    }

    public function getContentsDetailList($id)
    {
        return $this->domainQueryContentsDetail->selectContentsDetailList($id);
    }

    public function getContentsExtrasList($id)
    {
        return $this->domainQueryContentsExtras->selectContentsExtrasList($id);
    }

    public function setDetailContext($key, $val)
    {
        $this->detailContextPool[$key] = $val;
    }

    public function getDetailContext($key)
    {
        return isset($this->detailContextPool[$key])
            ? $this->detailContextPool[$key] : null;
    }

    public function addContentsExtras($contId, $entities)
    {
        return $this->domainQueryContentsExtras->deleteAndInsert($contId, $entities);
    }

    public function addContentsDetail($contId, $entities)
    {
        return $this->domainQueryContentsDetail->deleteAndInsert($contId, $entities);
    }

    public function addContents($entity)
    {
        return $this->domainQueryContents->insertRecord($entity);
    }

    public function delContents($id)
    {
        return $this->domainQueryContents->deleteRecord($id);
    }

    public function modPublish($entity)
    {
        return $this->domainQueryContents->updatePublish($entity);
    }

    private function parseDetailContext($path)
    {
        $simpleXmlLoader = simplexml_load_file($path);
        $context = new stdClass();
        $context->fieldsList = array();

        foreach ($simpleXmlLoader->attributes() as $rootKey => $rootVal)
            $context->$rootKey = $rootVal;
        foreach ($simpleXmlLoader->field as $nodeKey => $nodeVal) {
            $field = new stdClass();
            foreach ($nodeVal->attributes() as $attrKey => $attrVal)
                $field->$attrKey = $attrVal;
            $context->fieldsList[] = $field;
        }

        return $context;
    }

    public static function validateSequence($sequence)
    {
        $validator = new Zend_Validate_Int();
        return $validator->isValid($sequence);
    }
}
<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        return new Zend_Loader_Autoloader_Resource(array(
            'namespace' => 'Domain',
            'basePath' => APPLICATION_PATH . '/domain',
            'resourceTypes' => array(
                'service' => array("path" => "services/", "namespace" => "Service"),
                'query' => array("path" => "queries/", "namespace" => "Query"),
                'entity' => array("path" => "entities/", "namespace" => "Entity"),
                'search' => array("path" => "search/", "namespace" => "Search"),
                'enum' => array("path" => "enums/", "namespace" => "Enum"),
                'util' => array("path" => "utils/", "namespace" => "Util")
            )
        ));
    }

    protected function _initLogger()
    {
        $option = $this->getOption("logger");
        $writer = new Zend_Log_Writer_Stream($option["baseDir"] . '/' . date("Ymd") . '.log');
        $format = '%timestamp% %priorityName% (%priority%): %message% [File: %file%] [Line: %line%] Catched By [Module: %module%]-[Controller: %controller%]-[Action: %action%] ' . PHP_EOL;
        $writer->setFormatter(new Zend_Log_Formatter_Simple($format));
        $logger = new Zend_Log($writer);
        Zend_Registry::set('logger', $logger);
    }
}

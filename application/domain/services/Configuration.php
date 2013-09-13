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
class Domain_Service_Configuration
{
    private $domainQueryConfiguration = null;

    private $configurationPool = null;

    public function Domain_Service_Configuration()
    {
        $this->domainQueryConfiguration = new Domain_Query_Configuration();

        $this->setConfigurationPool();
    }

    private function setConfigurationPool()
    {
        $configList = $this->getConfigurationList();

        foreach ($configList as $config) {
            $configName = $config->getConfigName();
            $configText = $config->getConfigText();

            $this->configurationPool[$configName] = $configText;
        }
    }

    public function getConfigurationPool()
    {
        return $this->configurationPool;
    }

    private function getConfigurationList()
    {
        return $this->domainQueryConfiguration->selectConfigurationList();
    }

    public function getConfigurationView($configName)
    {
        return isset($this->configurationPool[$configName]) ?
            $this->configurationPool[$configName] : null;
    }

    public function setConfiguration($entities)
    {
        $result = $this->domainQueryConfiguration->deleteAndInsert(
            $entities);
        $this->setConfigurationPool();
        return $result;
    }
}
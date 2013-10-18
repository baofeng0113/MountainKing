<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_ControlpanelController extends Admin_BasicController
{
    public function navigatAction()
    {
        $domainServiceNavigat = new Domain_Service_Navigat();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $naviName = $request->getParam("naviname", array());
            $location = $request->getParam("location", array());
            $sequence = $request->getParam("sequence", array());

            if (count($naviName) == 0 || count($location) == 0 ||
                count($sequence) == 0
            ) {
                exit($this->buildErroResponse("修改失败，您没有设置任何网站导航项目"));
            } else {
                $navigatEntitiesList = array();
            }

            foreach ($naviName as $key => $val) {
                if (strlen(trim($naviName[$key])) == 0)
                    exit($this->buildErroResponse("修改失败，您还没有输入导航项目名称"));
                if (strlen(trim($location[$key])) == 0)
                    exit($this->buildErroResponse("修改失败，您还没有输入导航链接地址"));
                if (strlen(trim($sequence[$key])) == 0)
                    exit($this->buildErroResponse("修改失败，您还没有输入导航排序数字"));

                $navigatEntity = new Domain_Entity_Navigat();
                $navigatEntity->setNaviName($naviName[$key]);
                $navigatEntity->setLocation($location[$key]);
                $navigatEntity->setSequence($sequence[$key]);
                $navigatEntity->setCreatedTime(date("Y-m-d H:i:s"));
                $navigatEntity->setCreatedIp($request->getClientIp());
                $navigatEntity->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());

                $navigatEntitiesList[] = $navigatEntity;
            }

            try {
                $domainServiceNavigat->deleteAndInsert($navigatEntitiesList);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }

        $this->view->navigatList = $domainServiceNavigat->getNavigatList();
    }

    public function settingAction()
    {
        try {
            $domainServiceConfiguration = new Domain_Service_Configuration();
            $request = new Zend_Controller_Request_Http();
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);
            $entities = $this->parseSettingRequest($request);
            try {
                $domainServiceConfiguration->setConfiguration($entities);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
        $configList = $domainServiceConfiguration->getConfigurationPool();
        $this->view->configList = $configList;
    }

    public function viewlogAction()
    {
        $request = new Zend_Controller_Request_Http();
        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);
            $logFile = $request->getParam("logfile", "");
            if ($logFile == "" || !is_readable(APPLICATION_PATH . "/../repository/logs/" . $logFile))
                exit($this->buildErroResponse("读取失败，未获取到日志文件名或文件无法读取"));
            $logFile = APPLICATION_PATH . "/../repository/logs/" . $logFile;
            if (filesize($logFile) > 1024 * 1024)
                exit($this->buildErroResponse("读取失败，日志文件大于1M，请登录服务器查看"));
            exit($this->buildAjaxResponse(true, nl2br(file_get_contents($logFile))));
        } else {
            $scandir = scandir(APPLICATION_PATH . "/../repository/logs/");
            $logList = array();
            foreach ($scandir as $key => $val) {
                if (is_file(APPLICATION_PATH . "/../repository/logs/" . $val)) {
                    $path = realpath(APPLICATION_PATH . "/../repository/logs/" . $val);
                    $logList[$key]["path"] = str_replace("\\", "/", str_replace(
                        realpath(APPLICATION_PATH . "/../"), "", $path));
                    $logList[$key]["name"] = $val;
                    $logList[$key]["time"] = date("Y-m-d H:i:s", filemtime($path));
                    $logList[$key]["size"] = sprintf("%.4f", filesize($path) / 1024);
                }
            }
            $this->view->logList = $logList;
        }
    }

    private function parseSettingRequest($request)
    {
        $configurationEntity = new Domain_Entity_Configuration();
        $configurationEntity->setCreatedTime(date("Y-m-d H:i:s"));
        $configurationEntity->setCreatedIp($request->getClientIp());
        $configurationEntity->setCreatedUser(
            Domain_Util_General::getLoginAdministrator());
        $configKeyList = Domain_Enum_Configuration::keysList();
        $settingEntitiesList = array();
        foreach ($configKeyList as $key => $val) {
            $settingEntitiesList[$key] = clone $configurationEntity;
            $settingEntitiesList[$key]->setConfigName($val);
            $settingEntitiesList[$key]->setConfigText(
                $request->getParam($val, ""));
        }
        return $settingEntitiesList;
    }
}
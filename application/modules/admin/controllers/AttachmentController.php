<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_AttachmentController extends Admin_BasicController
{
    public function listAction()
    {
        $domainServiceAttachment = new Domain_Service_Attachment();
        $request = new Zend_Controller_Request_Http();

        $search = new Domain_Search_Attachment();
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        try {
            $domainServicePermission = new Domain_Service_Permission();
            $this->view->haveDelPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "attachment-del");

            $attachmentNums = $domainServiceAttachment->getAttachmentNums(
                $search);
            $this->view->attachmentNums = $attachmentNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($attachmentNums == 0) {
                $this->view->attachmentList = null;
            } else {
                $attachmentList = $domainServiceAttachment->getAttachmentList(
                    $search, ($page - 1) * 10, 10);
                foreach ($attachmentList as $key => $val)
                    $attachmentList[$key]->setFileSize(sprintf(
                        "%.4f", $val->getFileSize() / 1024));
                $this->view->attachmentList = $attachmentList;
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function deleteAction()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noViewRenderer', true);
        $request = new Zend_Controller_Request_Http();

        $id = $request->getParam("id", null);
        if (!$id || !is_numeric($id))
            exit($this->buildErroResponse("发生错误，系统未获取到附件ID参数；请返回重试"));

        try {
            $domainServiceAttachment = new Domain_Service_Attachment();
            $attachment = $domainServiceAttachment->getAttachmentView($id);

            if ($attachment == null)
                exit($this->buildErroResponse("发生错误，系统未获取到附件ID参数；请返回重试"));
            $domainServiceAttachment->delAttachment($id);
            @unlink($attachment->getServerPath() . '/' . $attachment->getServerURI1() .
                '/' . $attachment->getServerURI2());
            @unlink($attachment->getServerPath() . '/' . $attachment->getServerURI1() .
                '/thumb_' . $attachment->getServerURI2());
            exit($this->buildAjaxResponse(true, null));
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function createAction()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noViewRenderer', true);
        $request = new Zend_Controller_Request_Http();

        try {
            $domainServiceConfiguration = new Domain_Service_Configuration();
            $directory = date("Y/m/d");
            if (true == file_exists($domainServiceConfiguration->getConfigurationView(
                Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory)
            ) {
                if (!is_writable($domainServiceConfiguration->getConfigurationView(
                    Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory)
                )
                    exit($this->buildErroResponse("上传失败，文件夹无法写入请检查是否有写入权限"));
            } else {
                $isMakedDirectory = @mkdir($domainServiceConfiguration->getConfigurationView(
                    Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory, 0777, true);
                if (!$isMakedDirectory)
                    exit($this->buildErroResponse("上传失败，无法创建文件夹请检查是否有写入权限"));
            }
            $adapter = new Zend_File_Transfer_Adapter_Http(array("ignoreNoFile" => true));
            $adapter->setDestination($domainServiceConfiguration->getConfigurationView(
                Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory);
            $uploadedFileAttribute = $adapter->getFileInfo();
            if (!isset($uploadedFileAttribute["uploader"]["name"]) ||
                $uploadedFileAttribute["uploader"]["name"] == ""
            )
                exit($this->buildErroResponse("上传失败，为获取到上传文件或文件尺寸超过限制"));
            $uploadedPathAttribute = pathinfo($domainServiceConfiguration->getConfigurationView(
                Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' .
                $directory . '/' . $uploadedFileAttribute["uploader"]["name"]);
            $username = Domain_Util_General::getLoginAdministrator();
            $filename = md5($uploadedPathAttribute['basename'] . microtime() . rand(1111, 9999)) . '.' .
                $uploadedPathAttribute['extension'];
            $adapter->addFilter('Rename', array('target' => $filename, 'overwrite' => true));

            if (!$adapter->receive()) {
                exit($this->buildErroResponse(implode("", $adapter->getMessages())));
            } else {
                $domainServiceAttachment = new Domain_Service_Attachment();

                $attachmentEntity = new Domain_Entity_Attachment();
                $attachmentEntity->setFileName($uploadedFileAttribute["uploader"]["name"]);
                $attachmentEntity->setFileSize(filesize($domainServiceConfiguration->getConfigurationView(
                    Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' .
                    $directory . '/' . $filename));
                if (is_readable('/usr/share/file/magic'))
                    $attachmentEntity->setFileType(finfo_file(finfo_open(FILEINFO_MIME, '/usr/share/file/magic'),
                        $domainServiceConfiguration->getConfigurationView(
                        Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory . '/' . $filename));
                else
                    $attachmentEntity->setFileType(finfo_file(finfo_open(FILEINFO_MIME),
                        $domainServiceConfiguration->getConfigurationView(
                        Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory . '/' . $filename));
                $attachmentEntity->setSequence(0);
                $attachmentEntity->setServerHost($domainServiceConfiguration->getConfigurationView(
                    Domain_Enum_Configuration::ATTACHMENT_HOSTNAME));
                $attachmentEntity->setServerPath($domainServiceConfiguration->getConfigurationView(
                    Domain_Enum_Configuration::ATTACHMENT_ROOTPATH));
                $attachmentEntity->setServerURI1($directory);
                $attachmentEntity->setServerURI2($filename);
                $attachmentEntity->setDescription("");
                $attachmentEntity->setCreatedUser($username);
                $attachmentEntity->setCreatedIp($request->getClientIp());
                $attachmentEntity->setCreatedTime(date("Y-m-d H:i:s"));

                $domainServiceAttachment->addAttachment($attachmentEntity);

                if (mb_substr($attachmentEntity->getFileType(), 0, 5) == "image") {
                    $domainUtilThumb = new Domain_Util_Thumb($domainServiceConfiguration->getConfigurationView(
                        Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory . '/' . $filename);

                    $domainUtilThumb->deposit($domainServiceConfiguration->getConfigurationView(
                        Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory . '/' . $filename);

                    $domainUtilThumb = new Domain_Util_Thumb($domainServiceConfiguration->getConfigurationView(
                        Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory . '/' . $filename, 132, 108);

                    $domainUtilThumb->deposit($domainServiceConfiguration->getConfigurationView(
                        Domain_Enum_Configuration::ATTACHMENT_ROOTPATH) . '/' . $directory . '/thumb_' . $filename);
                }

                echo $this->buildAjaxResponse(true, $domainServiceConfiguration->getConfigurationView(
                    Domain_Enum_Configuration::ATTACHMENT_HOSTNAME) . '/' . $directory . '/' . $filename);
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }
}
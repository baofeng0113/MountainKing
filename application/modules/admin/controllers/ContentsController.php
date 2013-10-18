<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_ContentsController extends Admin_BasicController
{
    public function createAction()
    {
        $domainServiceCategory = new Domain_Service_Category();
        $domainServiceContents = new Domain_Service_Contents();

        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $viewType = $request->getParam("viewtype", '0');
            $category = $request->getParam("category", null);
            $keywords = $request->getParam("keywords", "");
            $description = $request->getParam("description", "");
            $sequence = $request->getParam("sequence", '0');
            $disabled = $request->getParam("disabled", '0');
            $subject = $request->getParam("subject", null);

            if (!$category)
                exit($this->buildErroResponse("添加失败，您还没有选择内容所属栏目；请重试"));

            if (!$subject)
                exit($this->buildErroResponse("添加失败，您还没有填写内容标题；请重试"));

            $categoryExtrasEntity = $domainServiceCategory->getCategoryExtrasView(
                $category, Domain_Enum_CategoryExtras::CATEGORY_EXTRA_PUBLISH);

            if (!$categoryExtrasEntity)
                exit($this->buildErroResponse("添加失败，您选择的栏目不存在；请重试"));

            $categoryAuth = false;
            $categoryList = $domainServiceCategory->getEditorCategoryList(
                Domain_Util_General::getLoginAdministrator());
            foreach ($categoryList as $key => $val) {
                if ($val->getId() == $category)
                    $categoryAuth = true;
            }

            if (!$categoryAuth)
                exit($this->buildErroResponse("添加失败，您没有权限添加该栏目的内容"));


            $publish = $categoryExtrasEntity->getParams() == '0' ? '1' : '0';

            $extraComment = $request->getParam("extra_comment", '0');
            $extraDigg = $request->getParam("extra_digg", '0');

            $detailModel = $domainServiceContents->getDetailContext($viewType);

            if ($detailModel == null)
                exit($this->buildErroResponse("添加失败，您选择的内容类型不存在请返回重试"));

            $detailMap = array();
            if ($detailModel->multiple == "true") {
                foreach ($detailModel->fieldsList as $key => $val) {
                    $field = $request->getParam((string)$val->column, null);
                    if ($val->empty == "false" && !$field) {
                        foreach ($field as $fieldKey => $fieldVal) {
                            if ($val->empty == "false" && !$fieldVal)
                                exit($this->buildErroResponse("添加失败，" . $val->name . "不能为空请返回重试"));
                            else
                                $detailMap[$fieldKey][(string)$val->column] = $fieldVal;
                        }
                    } else {
                        foreach ($field as $fieldKey => $fieldVal)
                            $detailMap[$fieldKey][(string)$val->column] = $fieldVal;
                    }
                }
            } else {
                foreach ($detailModel->fieldsList as $key => $val) {
                    $field = $request->getParam((string)$val->column, null);
                    if ($val->empty == "false" && !$field)
                        exit($this->buildErroResponse("添加失败，" . $val->name . "不能为空请返回重试"));
                    else
                        $detailMap[(string)$val->column] = $field;
                }
            }

            $domainEntityContents = new Domain_Entity_Contents();
            $domainEntityContents->setCategory($category);
            $domainEntityContents->setViewType($viewType);
            $domainEntityContents->setDisabled($disabled);
            $domainEntityContents->setKeywords($keywords);
            $domainEntityContents->setDescription($description);
            $domainEntityContents->setSequence($sequence);
            $domainEntityContents->setSubject($subject);
            $domainEntityContents->setPublish($publish);
            $domainEntityContents->setCreatedUser(Domain_Util_General::getLoginAdministrator());
            $domainEntityContents->setCreatedIp($request->getClientIp());
            $domainEntityContents->setCreatedTime(date("Y-m-d H:i:s"));
            $domainEntityContents->setUpdatedUser(Domain_Util_General::getLoginAdministrator());
            $domainEntityContents->setUpdatedIp($request->getClientIp());
            $domainEntityContents->setUpdatedTime(date("Y-m-d H:i:s"));

            try {
                $this->addKeywords($keywords);

                $contId = $domainServiceContents->addContents($domainEntityContents);

                $domainEntityContentsExtras1 = new Domain_Entity_ContentsExtras();
                $domainEntityContentsExtras1->setContId($contId);
                $domainEntityContentsExtras1->setParams($extraComment);
                $domainEntityContentsExtras1->setConfig(Domain_Enum_ContentsExtras::CONTENTS_EXTRA_COMMENT);
                $domainEntityContentsExtras1->setCreatedUser(Domain_Util_General::getLoginAdministrator());
                $domainEntityContentsExtras1->setCreatedIp($request->getClientIp());
                $domainEntityContentsExtras1->setCreatedTime(date("Y-m-d H:i:s"));

                $domainEntityContentsExtras2 = new Domain_Entity_ContentsExtras();
                $domainEntityContentsExtras2->setContId($contId);
                $domainEntityContentsExtras2->setParams($extraDigg);
                $domainEntityContentsExtras2->setConfig(Domain_Enum_ContentsExtras::CONTENTS_EXTRA_DIGG);
                $domainEntityContentsExtras2->setCreatedUser(Domain_Util_General::getLoginAdministrator());
                $domainEntityContentsExtras2->setCreatedIp($request->getClientIp());
                $domainEntityContentsExtras2->setCreatedTime(date("Y-m-d H:i:s"));

                $extras = array($domainEntityContentsExtras1, $domainEntityContentsExtras2);

                $domainServiceContents->addContentsExtras($contId, $extras);

                $detail = array();

                if ($detailModel->multiple == "true") {
                    foreach ($detailMap as $key => $val) {
                        $domainEntityContentsDetail = new Domain_Entity_ContentsDetail();
                        $domainEntityContentsDetail->setContId($contId);
                        $domainEntityContentsDetail->setDetail(json_encode($val));
                        $domainEntityContentsDetail->setCreatedUser(Domain_Util_General::getLoginAdministrator());
                        $domainEntityContentsDetail->setCreatedIp($request->getClientIp());
                        $domainEntityContentsDetail->setCreatedTime(date("Y-m-d H:i:s"));

                        $detail[$key] = $domainEntityContentsDetail;
                    }
                } else {
                    $domainEntityContentsDetail = new Domain_Entity_ContentsDetail();
                    $domainEntityContentsDetail->setContId($contId);
                    $domainEntityContentsDetail->setDetail(json_encode($detailMap));
                    $domainEntityContentsDetail->setCreatedUser(Domain_Util_General::getLoginAdministrator());
                    $domainEntityContentsDetail->setCreatedIp($request->getClientIp());
                    $domainEntityContentsDetail->setCreatedTime(date("Y-m-d H:i:s"));

                    $detail[0] = $domainEntityContentsDetail;
                }
                $domainServiceContents->addContentsDetail($contId, $detail);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }

        $viewType = intval($request->getParam("viewtype", '0'));

        $detailModel = $domainServiceContents->getDetailContext($viewType);

        if ($detailModel == null)
            exit($this->buildErroResponse("发生错误，您选择的内容类型不存在；请返回重试"));
        $this->view->detailModel = $detailModel;
        $this->view->viewType = $viewType;
        $this->view->typeList = Domain_Enum_ContentsDetail::iterator();
        $categoryList = $domainServiceCategory->getEditorCategoryList(
            Domain_Util_General::getLoginAdministrator());
        foreach ($categoryList as $key => $val) {
            $categoryList[$key]->setCateName(
                $domainServiceCategory->getCategoryNameMap($val));
        }
        $this->view->categoryList = $categoryList;

        $this->view->detailModel = $detailModel;
    }

    public function extrasAction()
    {
        $request = new Zend_Controller_Request_Http();

        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noViewRenderer', true);

        $id = $request->getParam("id", null);
        if (!$id || !is_numeric($id))
            exit($this->buildAjaxResponse(false, "发生错误，系统未获取到栏目ID参数；请返回重试"));

        $domainServiceCategory = new Domain_Service_Category();
        $extrasList = $domainServiceCategory->getCategoryExtrasList($id);
        if ($extrasList == null)
            exit($this->buildAjaxResponse(true, null));
        else {
            $returnList = array();
            foreach ($extrasList as $key => $val)
                $returnList[$val->getConfig()] = $val->getParams();
            exit($this->buildAjaxResponse(true, $returnList));
        }
    }

    public function publishAction()
    {
        $domainServiceContents = new Domain_Service_Contents();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $publish = $request->getParam('publish', array());

            if (!is_array($publish) || sizeof($publish) == 0)
                exit($this->buildErroResponse("审核失败，您至少需要选择一条内容信息进行操作"));

            try {
                foreach ($publish as $key => $val) {
                    $contentsId = intval($key);
                    if ($val == '1') {
                        $contents = new Domain_Entity_Contents();
                        $contents->setId($contentsId);
                        $contents->setPublish(true);
                        $contents->setUpdatedUser(Domain_Util_General::getLoginAdministrator());
                        $contents->setUpdatedIp($request->getClientIp());
                        $contents->setUpdatedTime(date("Y-m-d H:i:s"));
                        $domainServiceContents->modPublish($contents);
                    } else {
                        $contents = $domainServiceContents->getContentsView($contentsId);
                        if ($contents == null || $contents->getPublish())
                            continue;
                        else
                            $domainServiceContents->delContents($contentsId);
                    }
                }
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }

            exit($this->buildAjaxResponse(true, null));
        }

        $search = new Domain_Search_Contents();
        $search->setPublish(false);
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        $typeList = Domain_Enum_ContentsDetail::iterator();

        try {
            $contentsNums = $domainServiceContents->getContentsNums($search);
            $this->view->contentsNums = $contentsNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($contentsNums == 0) {
                $this->view->contentsList = null;
            } else {
                $contentsList = $domainServiceContents->getContentsList(
                    $search, ($page - 1) * 10, 10);
                $domainServiceCategory = new Domain_Service_Category();
                foreach ($contentsList as $key => $val) {
                    $contentsList[$key]->setViewType($typeList[$contentsList[$key]->getViewType()]['val']);
                    $category = $domainServiceCategory->getCategoryView($val->getCategory());
                    $contentsList[$key]->setCategory($category->getCateName());
                }
                $this->view->contentsList = $contentsList;
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function listAction()
    {
        $domainServiceContents = new Domain_Service_Contents();
        $domainServiceCategory = new Domain_Service_Category();
        $request = new Zend_Controller_Request_Http();

        $search = new Domain_Search_Contents();
        $search->setPublish(true);
        $search->setViewType(trim($request->getParam("viewtype", "")));
        $search->setContents(trim($request->getParam("contents", "")));
        $search->setCategory(trim($request->getParam("category", "")));
        $search->setDisabled(trim($request->getParam("disabled", "")));
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedIp(trim($request->getParam("created_ip", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        $this->view->requestViewType = trim($request->getParam("viewtype", ""));
        $this->view->requestContents = trim($request->getParam("contents", ""));
        $this->view->requestCategory = trim($request->getParam("category", ""));
        $this->view->requestDisabled = trim($request->getParam("disabled", ""));
        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedIp = trim($request->getParam("created_ip", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        $categoryNums = $domainServiceCategory->getCategoryNums(new Domain_Search_Category());
        $categoryList = $domainServiceCategory->getCategoryList(
            new Domain_Search_Category(), 0, $categoryNums);
        foreach ($categoryList as $key => $val) {
            $categoryList[$key]->setCateName($domainServiceCategory->getCategoryNameMap($val));
        }
        $this->view->categoryList = $categoryList;

        $this->view->typeList = $typeList = Domain_Enum_ContentsDetail::iterator();

        try {
            $contentsNums = $domainServiceContents->getContentsNums($search);
            $this->view->contentsNums = $contentsNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($contentsNums == 0) {
                $this->view->contentsList = null;
            } else {
                $contentsList = $domainServiceContents->getContentsList(
                    $search, ($page - 1) * 10, 10);
                $domainServiceCategory = new Domain_Service_Category();
                foreach ($contentsList as $key => $val) {
                    $contentsList[$key]->setViewType($typeList[$contentsList[$key]->getViewType()]['val']);
                    $category = $domainServiceCategory->getCategoryView($val->getCategory());
                    $contentsList[$key]->setCategory($category->getCateName());
                }
                $this->view->contentsList = $contentsList;
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function deleteAction()
    {
        $domainServiceContents = new Domain_Service_Contents();
        $domainServiceCategory = new Domain_Service_Category();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $id = $request->getParam('id', null);
            $id = $id === null ? null : intval(trim($id));
            if ($id === null || $id === "")
                exit($this->buildErroResponse("发生错误，系统未获取到内容ID参数；请返回重试"));

            try {
                $contents = $domainServiceContents->getContentsView($id);
                if ($contents == null)
                    exit($this->buildErroResponse("发生错误，您要删除的内容不存在；请返回重试"));

                $categoryAuth = false;
                $categoryList = $domainServiceCategory->getEditorCategoryList(
                    Domain_Util_General::getLoginAdministrator());
                foreach ($categoryList as $key => $val) {
                    if ($val->getId() == $contents->getCategory())
                        $categoryAuth = true;
                }
                if (!$categoryAuth)
                    exit($this->buildErroResponse("删除失败，您没有权限删除该栏目的内容"));

                $domainServiceContents->delContents($id);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
    }

    public function viewAction()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noViewRenderer', true);

        $domainServiceContents = new Domain_Service_Contents();
        $request = new Zend_Controller_Request_Http();

        $id = $request->getParam('id', null);
        $id = $id === null ? null : intval(trim($id));
        if ($id === null || $id === "")
            exit($this->buildErroResponse("发生错误，系统未获取到内容ID参数；请返回重试"));
        try {
            $contents = $domainServiceContents->getContentsView($id);
            if ($contents == null)
                exit($this->buildErroResponse("发生错误，您要查看的内容不存在；请返回重试"));
            header("Location:" . $request->getBasePath() . "/view-" . $contents->getId() . "-1.html");
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function editAction()
    {
        $domainServiceContents = new Domain_Service_Contents();
        $domainServiceCategory = new Domain_Service_Category();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $id = $request->getParam('id', null);
            $id = $id === null ? null : intval(trim($id));
            if ($id === null || $id === "")
                exit($this->buildErroResponse("发生错误，系统未获取到内容ID参数；请返回重试"));

            try {
                $domainServiceContents->delContents($id);
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }

            $viewType = $request->getParam("viewtype", '0');
            $category = $request->getParam("category", null);
            $keywords = $request->getParam("keywords", "");
            $description = $request->getParam("description", "");
            $disabled = $request->getParam("disabled", '0');
            $subject = $request->getParam("subject", null);
            $sequence = $request->getParam("sequence", '0');

            if (!$category)
                exit($this->buildErroResponse("编辑失败，您还没有选择内容所属栏目；请重试"));

            if (!$subject)
                exit($this->buildErroResponse("编辑失败，您还没有填写内容标题；请重试"));

            $categoryExtrasEntity = $domainServiceCategory->getCategoryExtrasView(
                $category, Domain_Enum_CategoryExtras::CATEGORY_EXTRA_PUBLISH);

            if (!$categoryExtrasEntity)
                exit($this->buildErroResponse("编辑失败，您选择的栏目不存在；请重试"));

            $categoryAuth = false;
            $categoryList = $domainServiceCategory->getEditorCategoryList(
                Domain_Util_General::getLoginAdministrator());
            foreach ($categoryList as $key => $val) {
                if ($val->getId() == $category)
                    $categoryAuth = true;
            }

            if (!$categoryAuth)
                exit($this->buildErroResponse("编辑失败，您没有权限添加该栏目的内容"));

            $publish = $categoryExtrasEntity->getParams() == '0' ? '1' : '0';

            $extraComment = $request->getParam("extra_comment", '0');
            $extraDigg = $request->getParam("extra_digg", '0');

            $detailModel = $domainServiceContents->getDetailContext($viewType);

            if ($detailModel == null)
                exit($this->buildErroResponse("编辑失败，您选择的内容类型不存在请返回重试"));

            $detailMap = array();
            if ($detailModel->multiple == "true") {
                foreach ($detailModel->fieldsList as $key => $val) {
                    $field = $request->getParam((string)$val->column, null);
                    if ($val->empty == "false" && !$field) {
                        foreach ($field as $fieldKey => $fieldVal) {
                            if ($val->empty == "false" && !$fieldVal)
                                exit($this->buildErroResponse("编辑失败，" . $val->name . "不能为空请返回重试"));
                            else
                                $detailMap[$fieldKey][(string)$val->column] = $fieldVal;
                        }
                    } else {
                        foreach ($field as $fieldKey => $fieldVal) {
                            $detailMap[$fieldKey][(string)$val->column] = $fieldVal;
                        }
                    }
                }
            } else {
                foreach ($detailModel->fieldsList as $key => $val) {
                    $field = $request->getParam((string)$val->column, null);
                    if ($val->empty == "false" && !$field)
                        exit($this->buildErroResponse("编辑失败，" . $val->name . "不能为空请返回重试"));
                    else
                        $detailMap[(string)$val->column] = $field;
                }
            }
            $domainEntityContents = new Domain_Entity_Contents();

            $domainEntityContents->setCategory($category);
            $domainEntityContents->setViewType($viewType);
            $domainEntityContents->setDisabled($disabled);
            $domainEntityContents->setKeywords($keywords);
            $domainEntityContents->setSequence($sequence);
            $domainEntityContents->setDescription($description);
            $domainEntityContents->setSubject($subject);
            $domainEntityContents->setPublish($publish);
            $domainEntityContents->setCreatedUser(Domain_Util_General::getLoginAdministrator());
            $domainEntityContents->setCreatedIp($request->getClientIp());
            $domainEntityContents->setCreatedTime(date("Y-m-d H:i:s"));
            $domainEntityContents->setUpdatedUser(Domain_Util_General::getLoginAdministrator());
            $domainEntityContents->setUpdatedIp($request->getClientIp());
            $domainEntityContents->setUpdatedTime(date("Y-m-d H:i:s"));

            try {
                $this->addKeywords($keywords);

                $contId = $domainServiceContents->addContents($domainEntityContents);

                $domainEntityContentsExtras1 = new Domain_Entity_ContentsExtras();
                $domainEntityContentsExtras1->setContId($contId);
                $domainEntityContentsExtras1->setParams($extraComment);
                $domainEntityContentsExtras1->setConfig(Domain_Enum_ContentsExtras::CONTENTS_EXTRA_COMMENT);
                $domainEntityContentsExtras1->setCreatedUser(Domain_Util_General::getLoginAdministrator());
                $domainEntityContentsExtras1->setCreatedIp($request->getClientIp());
                $domainEntityContentsExtras1->setCreatedTime(date("Y-m-d H:i:s"));

                $domainEntityContentsExtras2 = new Domain_Entity_ContentsExtras();
                $domainEntityContentsExtras2->setContId($contId);
                $domainEntityContentsExtras2->setParams($extraDigg);
                $domainEntityContentsExtras2->setConfig(Domain_Enum_ContentsExtras::CONTENTS_EXTRA_DIGG);
                $domainEntityContentsExtras2->setCreatedUser(Domain_Util_General::getLoginAdministrator());
                $domainEntityContentsExtras2->setCreatedIp($request->getClientIp());
                $domainEntityContentsExtras2->setCreatedTime(date("Y-m-d H:i:s"));

                $extras = array($domainEntityContentsExtras1, $domainEntityContentsExtras2);

                $domainServiceContents->addContentsExtras($contId, $extras);

                $detail = array();

                if ($detailModel->multiple == "true") {
                    foreach ($detailMap as $key => $val) {
                        $domainEntityContentsDetail = new Domain_Entity_ContentsDetail();
                        $domainEntityContentsDetail->setContId($contId);
                        $domainEntityContentsDetail->setDetail(json_encode($val));
                        $domainEntityContentsDetail->setCreatedUser(Domain_Util_General::getLoginAdministrator());
                        $domainEntityContentsDetail->setCreatedIp($request->getClientIp());
                        $domainEntityContentsDetail->setCreatedTime(date("Y-m-d H:i:s"));

                        $detail[] = $domainEntityContentsDetail;
                    }
                } else {
                    $domainEntityContentsDetail = new Domain_Entity_ContentsDetail();
                    $domainEntityContentsDetail->setContId($contId);
                    $domainEntityContentsDetail->setDetail(json_encode($detailMap));
                    $domainEntityContentsDetail->setCreatedUser(Domain_Util_General::getLoginAdministrator());
                    $domainEntityContentsDetail->setCreatedIp($request->getClientIp());
                    $domainEntityContentsDetail->setCreatedTime(date("Y-m-d H:i:s"));

                    $detail[] = $domainEntityContentsDetail;
                }
                $domainServiceContents->addContentsDetail($contId, $detail);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }

        $id = $request->getParam('id', null);
        $id = $id === null ? null : intval(trim($id));
        if ($id === null || $id === "")
            exit($this->buildErroResponse("发生错误，系统未获取到内容ID参数；请返回重试"));

        try {
            $contents = $domainServiceContents->getContentsView($id);
            if ($contents == null)
                exit($this->buildErroResponse("发生错误，您要编辑的内容不存在；请返回重试"));
            $this->view->contents = $contents;

            $viewType = intval($request->getParam("viewtype", $contents->getViewType()));

            $this->view->viewType = $viewType;
            $this->view->typeList = Domain_Enum_ContentsDetail::iterator();

            $this->view->detailModel = $domainServiceContents->getDetailContext($viewType);

            $categoryList = $domainServiceCategory->getEditorCategoryList(
                Domain_Util_General::getLoginAdministrator());
            foreach ($categoryList as $key => $val) {
                $categoryList[$key]->setCateName(
                    $domainServiceCategory->getCategoryNameMap($val));
            }
            $this->view->categoryList = $categoryList;

            $allowEdit = false;
            foreach ($categoryList as $key => $val) {
                if ($val->getId() == $contents->getCategory())
                    $allowEdit = true;
            }

            if (!$allowEdit)
                exit($this->buildErroResponse("发生错误，您不是该栏目的作者不能修改该内容"));

            $this->view->detail = $viewType == $contents->getViewType() ? $domainServiceContents->getContentsDetailList($id) : null;

            $extraList = $domainServiceContents->getContentsExtrasList($id);
            foreach ($extraList as $key => $val) {
                $extras[$val->getConfig()] = $val->getParams();
            }

            $this->view->extras = $extras;

        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    private function addKeywords($keywords)
    {
        $domainServiceKeywords = new Domain_Service_Keywords();

        if (mb_strlen(trim($keywords)) == 0) return;
        $keywordList = explode(",", $keywords);
        $request = new Zend_Controller_Request_Http();
        foreach ($keywordList as $val) {
            if (mb_strlen(trim($val)) == 0) continue;
            $entity = new Domain_Entity_Keywords();
            $entity->setKeyword($val);
            $entity->setCreatedUser(Domain_Util_General::getLoginAdministrator());
            $entity->setCreatedIp($request->getClientIp());
            $entity->setCreatedTime(date("Y-m-d H:i:s"));
            $domainServiceKeywords->addKeywords($entity);
        }
    }
}
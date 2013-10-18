<?php

include_once dirname(__FILE__) . "/./BasicController.php";

class Admin_CategoryController extends Admin_BasicController
{
    public function createAction()
    {
        $domainServiceCategory = new Domain_Service_Category();
        $domainServiceTemplate = new Domain_Service_Template();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $description = $request->getParam("description", "");
            $cateName = $request->getParam("catename", "");
            $cateCode = $request->getParam("catecode", "");
            $cateLogo = $request->getParam("catelogo", "");
            $cateType = $request->getParam("catetype", "");
            $sequence = $request->getParam("sequence", "");
            $template = $request->getParam("template", "");
            $keywords = $request->getParam("keywords", "");
            $disabled = $request->getParam("disabled", "");

            $extraPublish = intval($request->getParam("extra_publish", 1));
            $extraDigg = intval($request->getParam("extra_digg", 1));
            $extraComment = intval($request->getParam("extra_comment", 1));

            if (!Domain_Service_Category::validateCateName($cateName))
                exit($this->buildErroResponse("添加失败，您还没有输入栏目名称或格式错误"));
            if (!Domain_Service_Category::validateDisabled($disabled))
                exit($this->buildErroResponse("添加失败，您还没有选择是否启用该栏目"));
            if (!Domain_Service_Category::validateSequence($sequence))
                exit($this->buildErroResponse("添加失败，您还没有输入排序值或格式错误"));
            if (!Domain_Service_Category::validateCateType($cateType))
                exit($this->buildErroResponse("添加失败，您选择的栏目类型不存在"));

            try {
                if ($cateCode !== "" && !$domainServiceCategory->isCategoryCodeExist($cateCode))
                    exit($this->buildErroResponse("添加失败，您选择的上级栏目不存在"));
                if ($domainServiceCategory->isCategoryNameExist($cateName, null))
                    exit($this->buildErroResponse("添加失败，您填写的栏目名称已经存在"));
                $cateCode = $domainServiceCategory->buildNewCateogryCode($cateCode);
                if ($cateCode == false)
                    exit($this->buildErroResponse("添加失败，当前栏目已经无法再创建子栏目"));
                if ($domainServiceTemplate->isDirectoryExist($template) == false)
                    exit($this->buildErroResponse("添加失败，您选择的模板风格不存在"));

                $domainEntityCategory = new Domain_Entity_Category();
                $domainEntityCategory->setCateName($cateName);
                $domainEntityCategory->setCateCode($cateCode);
                $domainEntityCategory->setCateType($cateType);
                $domainEntityCategory->setCateLogo($cateLogo);
                $domainEntityCategory->setDisabled($disabled);
                $domainEntityCategory->setSequence($sequence);
                $domainEntityCategory->setTemplate($template);
                $domainEntityCategory->setKeywords($keywords);
                $domainEntityCategory->setDescription($description);
                $domainEntityCategory->setCreatedTime(date("Y-m-d H:i:s"));
                $domainEntityCategory->setCreatedIp($request->getClientIp());
                $domainEntityCategory->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());
                $domainEntityCategory->setUpdatedTime(date("Y-m-d H:i:s"));
                $domainEntityCategory->setUpdatedIp($request->getClientIp());
                $domainEntityCategory->setUpdatedUser(
                    Domain_Util_General::getLoginAdministrator());

                $categoryId = $domainServiceCategory->addCategory($domainEntityCategory);

                $categoryExtrasEntities = array();
                $categoryExtrasEntity1 = new Domain_Entity_CategoryExtras();
                $categoryExtrasEntity1->setCateId($categoryId);
                $categoryExtrasEntity1->setConfig(
                    Domain_Enum_CategoryExtras::CATEGORY_EXTRA_COMMENT);
                $categoryExtrasEntity1->setParams($extraComment);
                $categoryExtrasEntity1->setCreatedTime(date("Y-m-d H:i:s"));
                $categoryExtrasEntity1->setCreatedIp($request->getClientIp());
                $categoryExtrasEntity1->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());

                $categoryExtrasEntity2 = new Domain_Entity_CategoryExtras();
                $categoryExtrasEntity2->setCateId($categoryId);
                $categoryExtrasEntity2->setConfig(
                    Domain_Enum_CategoryExtras::CATEGORY_EXTRA_DIGG);
                $categoryExtrasEntity2->setParams($extraDigg);
                $categoryExtrasEntity2->setCreatedTime(date("Y-m-d H:i:s"));
                $categoryExtrasEntity2->setCreatedIp($request->getClientIp());
                $categoryExtrasEntity2->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());

                $categoryExtrasEntity3 = new Domain_Entity_CategoryExtras();
                $categoryExtrasEntity3->setCateId($categoryId);
                $categoryExtrasEntity3->setConfig(
                    Domain_Enum_CategoryExtras::CATEGORY_EXTRA_PUBLISH);
                $categoryExtrasEntity3->setParams($extraPublish);
                $categoryExtrasEntity3->setCreatedTime(date("Y-m-d H:i:s"));
                $categoryExtrasEntity3->setCreatedIp($request->getClientIp());
                $categoryExtrasEntity3->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());

                $categoryExtrasEntities[] = $categoryExtrasEntity1;
                $categoryExtrasEntities[] = $categoryExtrasEntity2;
                $categoryExtrasEntities[] = $categoryExtrasEntity3;

                $domainServiceCategory->addCategoryExtras($categoryId, $categoryExtrasEntities);

                exit($this->buildAjaxResponse(TRUE, $categoryId));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }
        $this->view->typeList = Domain_Enum_CategoryTypes::iterator();

        $domainSearchCategory = new Domain_Search_Category();
        $domainSearchCategory->setDisabled(false);
        try {
            $templateList = $domainServiceTemplate->getTemplateList();
            $this->view->templateList = $templateList;
            $categoryNums = $domainServiceCategory->getCategoryNums($domainSearchCategory);
            $categoryList = $domainServiceCategory->getCategoryList(
                $domainSearchCategory, 0, $categoryNums);
            foreach ($categoryList as $key => $val) {
                $categoryList[$key]->setCateName($domainServiceCategory->getCategoryNameMap($val));
            }
            $this->view->categoryList = $categoryList;
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function deleteAction()
    {
        $request = new Zend_Controller_Request_Http();
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noViewRenderer', true);

        $id = $request->getParam('id', null);
        $id = $id === null ? null : intval(trim($id));
        if ($id === null || $id === "")
            exit($this->buildErroResponse("发生错误，系统未获取到栏目ID参数；请返回重试"));

        $domainServiceCategory = new Domain_Service_Category();
        $domainServiceContents = new Domain_Service_Contents();

        try {
            $category = $domainServiceCategory->getCategoryView($id);
            if ($category == null)
                exit($this->buildErroResponse("发生错误，您要查看的栏目不存在；请返回重试"));
            $domainSearchCategory = new Domain_Search_Category();
            $domainSearchCategory->setCateCode($category->getCateCode());
            if ($domainServiceCategory->getCategoryNums($domainSearchCategory) > 0)
                exit($this->buildErroResponse("删除失败，该栏目下存在子栏目请先移除子栏目"));
            $domainSearchContents = new Domain_Search_Contents();
            $domainSearchContents->setCategory($id);
            if ($domainServiceContents->getContentsNums($domainSearchContents) > 0)
                exit($this->buildErroResponse("删除失败，该栏目下存在内容信息无法进行删除"));
            $domainServiceCategory->delCategory($id);
            exit($this->buildAjaxResponse(true, null));
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function editorAction()
    {
        $domainServiceAdministrator = new Domain_Service_Administrator();
        $domainServiceCategory = new Domain_Service_Category();
        $domainServiceTemplate = new Domain_Service_Template();
        $request = new Zend_Controller_Request_Http();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            $id = $request->getParam('id', null);
            $id = $id === null ? null : intval(trim($id));
            if ($id === null || $id === "")
                exit($this->buildErroResponse("发生错误，系统未获取到栏目ID参数；请返回重试"));
            try {
                $category = $domainServiceCategory->getCategoryView($id);
                if ($category == null)
                    exit($this->buildErroResponse("发生错误，您要查看的栏目不存在；请返回重试"));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }

            $editorList = $request->getParam('editor', array());

            $entities = array();

            foreach ($editorList as $key => $val) {
                $domainEntityCategoryEditor = new Domain_Entity_CategoryEditor();
                $domainEntityCategoryEditor->setCateId($id);
                $domainEntityCategoryEditor->setEditor($val);
                $domainEntityCategoryEditor->setCreatedTime(date("Y-m-d H:i:s"));
                $domainEntityCategoryEditor->setCreatedIp($request->getClientIp());
                $domainEntityCategoryEditor->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());
                $entities[$key] = $domainEntityCategoryEditor;
            }

            try {
                $domainServiceCategory->addCategoryEditor($id, $entities);
                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }

        $id = $request->getParam('id', null);
        $id = $id === null ? null : intval(trim($id));
        if ($id === null || $id === "")
            exit($this->buildErroResponse("发生错误，系统未获取到栏目ID参数；请返回重试"));

        try {
            $category = $domainServiceCategory->getCategoryView($id);

            if ($category == null)
                exit($this->buildErroResponse("发生错误，您要查看的栏目不存在；请返回重试"));

            $category->setCateType(Domain_Enum_CategoryTypes::descript(
                $category->getCateType()));
            $category->setCateCode($domainServiceCategory->getCategoryNameMap(
                $category));
            $template = $domainServiceTemplate->getTemplateView(
                $category->getTemplate());
            $category->setTemplate($template->getThemeName());
            $this->view->category = $category;

            $categoryEditorList = $domainServiceCategory->getCategoryEditorList($id);
            if ($categoryEditorList !== null) {
                $convertList = array();
                foreach ($categoryEditorList as $key => $val) {
                    $convertList[] = $val->getEditor();
                }
            } else {
                $convertList = array();
            }
            $this->view->categoryEditorList = $convertList;
            $domainSearchAdminAccounts = new Domain_Search_AdminAccounts();
            $adminAccountsNums = $domainServiceAdministrator->getAdminAccountsNums(
                $domainSearchAdminAccounts);
            $this->view->adminAccountsList = $domainServiceAdministrator->getAdminAccountsList(
                $domainSearchAdminAccounts, 0, $adminAccountsNums);
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function listAction()
    {
        $request = new Zend_Controller_Request_Http();

        $this->view->requestCateName = trim($request->getParam("catename", ""));
        $this->view->requestCateType = trim($request->getParam("catetype", ""));
        $this->view->requestDisabled = trim($request->getParam("disabled", ""));
        $this->view->requestCreatedTime1 = trim($request->getParam("created_time1", ""));
        $this->view->requestCreatedUser = trim($request->getParam("created_user", ""));
        $this->view->requestCreatedTime2 = trim($request->getParam("created_time2", ""));

        $search = new Domain_Search_Category();
        $search->setCateName(trim($request->getParam("catename", "")));
        $search->setCateType(trim($request->getParam("catetype", "")));
        $search->setDisabled(trim($request->getParam("disabled", "")));
        $search->setCreatedTime1(trim($request->getParam("created_time1", "")));
        $search->setCreatedUser(trim($request->getParam("created_user", "")));
        $search->setCreatedTime2(trim($request->getParam("created_time2", "")));

        try {
            $domainServicePermission = new Domain_Service_Permission();
            $this->view->haveEditorPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "category-editor");
            $this->view->haveModPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "category-mod");
            $this->view->haveDelPermission = $domainServicePermission->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "category-del");

            $this->view->typeList = Domain_Enum_CategoryTypes::iterator();

            $domainServiceCategory = new Domain_Service_Category();
            $categoryNums = $domainServiceCategory->getCategoryNums(
                $search);
            $this->view->categoryNums = $categoryNums;

            $page = max(intval($request->getParam("page", 1)), 1);
            $this->view->limit = 10;
            $this->view->page = $page;

            if ($categoryNums == 0) {
                $this->view->categoryList = null;
            } else {
                $categoryList = $domainServiceCategory->getCategoryList(
                    $search, ($page - 1) * 10, 10);
                $convertedList = array();
                foreach ($categoryList as $key => $val) {
                    $val->setCateType(Domain_Enum_CategoryTypes::descript($val->getCateType()));
                    $domainServiceTemplate = new Domain_Service_Template();
                    $template = $domainServiceTemplate->getTemplateView($val->getTemplate());
                    $val->setTemplate($template->getThemeName());
                    $convertedList[$key]["category"] = $val;
                    $convertedList[$key]["extras"]["comment"] = $domainServiceCategory->getCategoryExtrasView(
                        $val->getId(), Domain_Enum_CategoryExtras::CATEGORY_EXTRA_COMMENT);
                    $convertedList[$key]["extras"]["digg"] = $domainServiceCategory->getCategoryExtrasView(
                        $val->getId(), Domain_Enum_CategoryExtras::CATEGORY_EXTRA_DIGG);
                    $convertedList[$key]["extras"]["publish"] = $domainServiceCategory->getCategoryExtrasView(
                        $val->getId(), Domain_Enum_CategoryExtras::CATEGORY_EXTRA_PUBLISH);
                }
                $this->view->categoryList = $convertedList;
            }
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function viewAction()
    {
        $request = new Zend_Controller_Request_Http();
        $id = $request->getParam('id', null);
        $id = $id === null ? null : intval(trim($id));

        if ($id === null || $id === "")
            exit($this->buildErroResponse("发生错误，系统未获取到栏目ID参数；请返回重试"));

        $domainServiceCategory = new Domain_Service_Category();
        $domainServiceTemplate = new Domain_Service_Template();

        try {
            $domainPermissionService = new Domain_Service_Permission();
            $this->view->haveModPermission = $domainPermissionService->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "category-mod");
            $this->view->haveDelPermission = $domainPermissionService->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "category-del");
            $this->view->haveEditorPermission = $domainPermissionService->getPermissionByAuth(
                Domain_Util_General::getLoginAdministrator(), "category-editor");

            $category = $domainServiceCategory->getCategoryView($id);

            if ($category == null)
                exit($this->buildErroResponse("发生错误，您要查看的栏目不存在；请返回重试"));

            $category->setCateType(Domain_Enum_CategoryTypes::descript(
                $category->getCateType()));
            $category->setCateCode($domainServiceCategory->getCategoryNameMap(
                $category));
            $template = $domainServiceTemplate->getTemplateView(
                $category->getTemplate());
            $category->setTemplate($template->getThemeName());
            $this->view->category = $category;

            $categoryExtra = $domainServiceCategory->getCategoryExtrasView(
                $id, Domain_Enum_CategoryExtras::CATEGORY_EXTRA_COMMENT);
            $this->view->categoryExtraComment = $categoryExtra === null ?
                1 : $categoryExtra->getParams();
            $categoryExtra = $domainServiceCategory->getCategoryExtrasView(
                $id, Domain_Enum_CategoryExtras::CATEGORY_EXTRA_DIGG);
            $this->view->categoryExtraDigg = $categoryExtra === null ?
                1 : $categoryExtra->getParams();
            $categoryExtra = $domainServiceCategory->getCategoryExtrasView(
                $id, Domain_Enum_CategoryExtras::CATEGORY_EXTRA_PUBLISH);
            $this->view->categoryExtraPublish = $categoryExtra === null ?
                1 : $categoryExtra->getParams();

            $categoryEditorList = $domainServiceCategory->getCategoryEditorList($id);
            if ($categoryEditorList !== null) {
                $convertList = array();
                $domainServiceAdministrator = new Domain_Service_Administrator();
                foreach ($categoryEditorList as $key => $val) {
                    $editorEntity = $domainServiceAdministrator->getAdminAccountView(
                        $val->getEditor());
                    if ($editorEntity === null) continue;
                    $convertList[] = $editorEntity;
                }
            }
            $this->view->categoryEditorList = $convertList;
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }

    public function editAction()
    {
        $request = new Zend_Controller_Request_Http();
        $id = $request->getParam('id', null);
        $id = $id === null ? null : intval(trim($id));

        $domainServiceCategory = new Domain_Service_Category();
        $domainServiceTemplate = new Domain_Service_Template();

        if (strtoupper($request->getMethod()) == "POST") {
            $front = Zend_Controller_Front::getInstance();
            $front->setParam('noViewRenderer', true);

            if ($id === null || $id === "")
                exit($this->buildErroResponse("发生错误，系统未获取到栏目ID参数；请返回重试"));
            $categoryEntity = $domainServiceCategory->getCategoryView($id);
            if ($categoryEntity == null)
                exit($this->buildErroResponse("发生错误，您要编辑的栏目不存在；请返回重试"));

            $description = $request->getParam("description", "");
            $cateName = $request->getParam("catename", "");
            $cateLogo = $request->getParam("catelogo", "");
            $cateType = $request->getParam("catetype", "");
            $sequence = $request->getParam("sequence", "");
            $template = $request->getParam("template", "");
            $keywords = $request->getParam("keywords", "");
            $disabled = $request->getParam("disabled", "");

            $extraPublish = intval($request->getParam("extra_publish", 1));
            $extraDigg = intval($request->getParam("extra_digg", 1));
            $extraComment = intval($request->getParam("extra_comment", 1));

            if (!Domain_Service_Category::validateCateName($cateName))
                exit($this->buildErroResponse("编辑失败，您还没有输入栏目名称或格式错误"));
            if (!Domain_Service_Category::validateDisabled($disabled))
                exit($this->buildErroResponse("编辑失败，您还没有选择是否启用该栏目"));
            if (!Domain_Service_Category::validateSequence($sequence))
                exit($this->buildErroResponse("编辑失败，您还没有输入排序值或格式错误"));
            if (!Domain_Service_Category::validateCateType($cateType))
                exit($this->buildErroResponse("编辑失败，您选择的栏目类型不存在"));

            try {
                if ($domainServiceCategory->isCategoryNameExist($cateName, $id))
                    exit($this->buildErroResponse("编辑失败，您填写的栏目名称已经存在"));
                if ($domainServiceTemplate->isDirectoryExist($template) == false)
                    exit($this->buildErroResponse("编辑失败，您选择的模板风格不存在"));

                $categoryEntity = new Domain_Entity_Category();
                $categoryEntity->setId($id);
                $categoryEntity->setDescription($description);
                $categoryEntity->setCateName($cateName);
                $categoryEntity->setCateType($cateType);
                $categoryEntity->setCateLogo($cateLogo);
                $categoryEntity->setDisabled($disabled);
                $categoryEntity->setSequence($sequence);
                $categoryEntity->setTemplate($template);
                $categoryEntity->setKeywords($keywords);
                $categoryEntity->setUpdatedTime(date("Y-m-d H:i:s"));
                $categoryEntity->setUpdatedIp($request->getClientIp());
                $categoryEntity->setUpdatedUser(
                    Domain_Util_General::getLoginAdministrator());

                $domainServiceCategory->modCategory($categoryEntity);
                $categoryExtrasEntities = array();
                $categoryExtrasEntity1 = new Domain_Entity_CategoryExtras();
                $categoryExtrasEntity1->setCateId($id);
                $categoryExtrasEntity1->setConfig(
                    Domain_Enum_CategoryExtras::CATEGORY_EXTRA_COMMENT);
                $categoryExtrasEntity1->setParams($extraComment);
                $categoryExtrasEntity1->setCreatedTime(date("Y-m-d H:i:s"));
                $categoryExtrasEntity1->setCreatedIp($request->getClientIp());
                $categoryExtrasEntity1->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());

                $categoryExtrasEntity2 = new Domain_Entity_CategoryExtras();
                $categoryExtrasEntity2->setCateId($id);
                $categoryExtrasEntity2->setConfig(
                    Domain_Enum_CategoryExtras::CATEGORY_EXTRA_DIGG);
                $categoryExtrasEntity2->setParams($extraDigg);
                $categoryExtrasEntity2->setCreatedTime(date("Y-m-d H:i:s"));
                $categoryExtrasEntity2->setCreatedIp($request->getClientIp());
                $categoryExtrasEntity2->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());

                $categoryExtrasEntity3 = new Domain_Entity_CategoryExtras();
                $categoryExtrasEntity3->setCateId($id);
                $categoryExtrasEntity3->setConfig(
                    Domain_Enum_CategoryExtras::CATEGORY_EXTRA_PUBLISH);
                $categoryExtrasEntity3->setParams($extraPublish);
                $categoryExtrasEntity3->setCreatedTime(date("Y-m-d H:i:s"));
                $categoryExtrasEntity3->setCreatedIp($request->getClientIp());
                $categoryExtrasEntity3->setCreatedUser(
                    Domain_Util_General::getLoginAdministrator());

                $categoryExtrasEntities[] = $categoryExtrasEntity1;
                $categoryExtrasEntities[] = $categoryExtrasEntity2;
                $categoryExtrasEntities[] = $categoryExtrasEntity3;

                $domainServiceCategory->addCategoryExtras($id, $categoryExtrasEntities);

                exit($this->buildAjaxResponse(true, null));
            } catch (Exception $exception) {
                $this->disposeCatchedException($exception);
            }
        }

        if ($id === null || $id === "")
            exit($this->buildErroResponse("发生错误，系统未获取到栏目ID参数请返回重试"));

        $this->view->typeList = Domain_Enum_CategoryTypes::iterator();

        try {
            $templateList = $domainServiceTemplate->getTemplateList();
            $this->view->templateList = $templateList;
            $categoryEntity = $domainServiceCategory->getCategoryView($id);
            if ($categoryEntity == null)
                exit($this->buildErroResponse("发生错误，您要查看的栏目不存在请返回重试"));
            $this->view->category = $categoryEntity;

            $categoryExtra = $domainServiceCategory->getCategoryExtrasView(
                $id, Domain_Enum_CategoryExtras::CATEGORY_EXTRA_COMMENT);
            $this->view->categoryExtraComment = $categoryExtra === null ?
                1 : $categoryExtra->getParams();
            $categoryExtra = $domainServiceCategory->getCategoryExtrasView(
                $id, Domain_Enum_CategoryExtras::CATEGORY_EXTRA_DIGG);
            $this->view->categoryExtraDigg = $categoryExtra === null ?
                1 : $categoryExtra->getParams();
            $categoryExtra = $domainServiceCategory->getCategoryExtrasView(
                $id, Domain_Enum_CategoryExtras::CATEGORY_EXTRA_PUBLISH);
            $this->view->categoryExtraPublish = $categoryExtra === null ?
                1 : $categoryExtra->getParams();
        } catch (Exception $exception) {
            $this->disposeCatchedException($exception);
        }
    }
}
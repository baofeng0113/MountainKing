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
class Domain_Service_Category
{
    private $domainQueryCategoryEditor = null;

    private $domainQueryCategory = null;

    private $domainQueryCategoryExtras = null;

    public function Domain_Service_Category()
    {
        $this->domainQueryCategoryEditor = new Domain_Query_CategoryEditor();

        $this->domainQueryCategory = new Domain_Query_Category();

        $this->domainQueryCategoryExtras = new Domain_Query_CategoryExtras();
    }

    public function addCategoryExtras($cateId, $entities)
    {
        return $this->domainQueryCategoryExtras->deleteAndInsert($cateId, $entities);
    }

    public function delCategoryExtras($cateId)
    {
        return $this->domainQueryCategoryExtras->deleteRecord($cateId);
    }

    public function addCategoryEditor($cateId, $entities)
    {
        return $this->domainQueryCategoryEditor->deleteAndInsert($cateId, $entities);
    }

    public function delCategoryEditor($cateId)
    {
        return $this->domainQueryCategoryEditor->deleteRecord($cateId);
    }

    public function addCategory($entity)
    {
        return $this->domainQueryCategory->insertRecord($entity);
    }

    public function modCategory($entity)
    {
        return $this->domainQueryCategory->updateRecord($entity);
    }

    public function delCategory($id)
    {
        return $this->domainQueryCategory->deleteRecord($id);
    }

    public function getCategoryList($search, $offset = 0, $limit = 30)
    {
        return $this->domainQueryCategory->selectCategoryList($search, $offset, $limit);
    }

    public function getCategoryNums($search)
    {
        return $this->domainQueryCategory->selectCategoryNums($search);
    }

    public function getCategoryView($id)
    {
        return $this->domainQueryCategory->selectCategoryViewById($id);
    }

    public function getCategoryEditorList($cateId)
    {
        return $this->domainQueryCategoryEditor->selectCategoryEditorList($cateId);
    }

    public function getEditorCategoryList($editor)
    {
        $editorList = $this->domainQueryCategoryEditor->selectEditorCategoryList(
            $editor);
        if ($editorList == null) return null;
        $categoryList = array();
        foreach ($editorList as $key => $val) {
            $category = $this->getCategoryView($val->getCateId());
            if ($category == null) continue;
            $categoryList[] = $category;
        }
        return $categoryList;
    }

    public function getCategoryExtrasList($cateId)
    {
        return $this->domainQueryCategoryExtras->selectCategoryExtrasList($cateId);
    }

    public function getCategoryExtrasView($cateId, $config)
    {
        return $this->domainQueryCategoryExtras->selectCategoryExtrasView($cateId, $config);
    }

    public function isCategoryCodeExist($cateCode)
    {
        return $this->domainQueryCategory->selectCategoryCodeExist(
            $cateCode) == 0 ? false : true;
    }

    public function isCategoryNameExist($cateName, $cateId)
    {
        return $this->domainQueryCategory->selectCategoryNameExist(
            $cateName, $cateId) == 0 ? false : true;
    }

    public function buildNewCateogryCode($cateCode)
    {
        $nextCategoryMaxCode = $this->domainQueryCategory->selectNextCategoryMaxCode(
            $cateCode);
        if ($nextCategoryMaxCode == null)
            return $cateCode . "001";
        if (substr($nextCategoryMaxCode, strlen($nextCategoryMaxCode) - 3, 3) == "999")
            return false;
        else
            return sprintf("%0" . ceil(strlen($nextCategoryMaxCode) / 3) * 3 . "d",
                $nextCategoryMaxCode + 1);
    }

    public function getCategoryNameMap($entity)
    {
        $categoryNameMap = array();
        $cateCode = $entity->getCateCode();
        for ($i = 0; $i < strlen($cateCode) / 3; $i++) {
            $category = $this->domainQueryCategory->selectCategoryViewByCode(
                (string)substr($entity->getCateCode(), 0, ($i + 1) * 3));
            $categoryNameMap[] = $category->getCateName();
        }
        return $categoryNameMap;
    }

    public function getCategoryPathMap($entity)
    {
        $categoryPathMap = array();
        $cateCode = $entity->getCateCode();
        for ($i = 0; $i < strlen($cateCode) / 3; $i++)
            $categoryPathMap[] = $this->domainQueryCategory->selectCategoryViewByCode(
                (string)substr($entity->getCateCode(), 0, ($i + 1) * 3));
        return $categoryPathMap;
    }

    public static function validateCateName($cateName)
    {
        return $cateName === null || $cateName === "" ? false : true;
    }

    public static function validateDisabled($disabled)
    {
        $validator = new Zend_Validate_Int();
        return $validator->isValid($disabled);
    }

    public static function validateSequence($sequence)
    {
        $validator = new Zend_Validate_Int();
        return $validator->isValid($sequence);
    }

    public static function validateCateType($cateType)
    {
        if ($cateType === "" || $cateType === null)
            return false;
        $cateList = Domain_Enum_CategoryTypes::keysList();
        return in_array($cateType, $cateList);
    }
}
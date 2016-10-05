<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib\Category;

class CategoryManager implements \core\IService
{
    /**
     * @var \modules\miscellaneous\lib\TreeData
     */
    private $treeData;

	/**
	 * @var CategoryDBManager
	 */
	protected $dbManager;

	public function init() // singleton service init();
	{
		$this->dbManager = new CategoryDBManager();
	}

	function getAllCategoriesInfo()
	{
		return $this->dbManager->getAllCategoriesInfo();
	}
	
	function saveCategory($category)
	{
		return $this->dbManager->saveCategory($category);
	}
	
	function getInfoBySID($category_sid)
	{
		return $this->dbManager->getInfoBySID($category_sid);
	}
	
	function deleteCategoryBySID($category_sid)
	{
		$children_info = $this->getChildren($category_sid);
		foreach($children_info as $child_info)
		{
			$this->deleteCategoryBySID($child_info['sid']);
		}
		\App()->ListingFieldManager->deleteListingFieldsByCategorySID($category_sid);
		$this->dbManager->deleteCategoryBySID($category_sid);
	}
	
	function getCategorySIDByID($category_id)
	{	
		return $this->dbManager->getCategorySIDByID($category_id);	
	}
	
	function getCategoryIDBySID($category_sid)
	{		
		return $this->dbManager->getCategoryIDBySID($category_sid);		
	}
	
	function getChildrenTemplateStructure($parent_sid)
	{
		return \App()->CategoryTree->getChildren($parent_sid);
	}
	
	function isRoot($sid) {
		return $sid == $this->getRootId();
	}
	
	function getRootId() {
		return 0;
	}
	
	function getObjectBySID($sid)
	{
		return $this->getCategory($this->getInfoBySID($sid));	
	}
	
	function getObjectByID($id) {
		return $this->getObjectBySID($this->getCategorySIDByID($id));	
	}
	
	function doesCategoryExist($category_sid)
	{
		if (is_numeric($category_sid))
		{
			$query_result = \App()->DB->getSingleValue('SELECT COUNT(*) FROM `classifieds_categories` WHERE sid = ?n', $category_sid);
			return $query_result == 1;
		}
		return false;
	}
	
	function getOrder($category_sid)
	{
		$query_result = \App()->DB->getSingleValue('SELECT `order` FROM `classifieds_categories` WHERE sid = ?n', $category_sid);
		return $query_result;
	}
	
	function getParentSID($categorySid)
	{
		return \App()->DB->getSingleValue('SELECT `parent` FROM `classifieds_categories` WHERE `sid` = ?n', $categorySid);
	}
	
	function getChildren($category_sid)
	{
		return \App()->DB->query('SELECT * FROM `classifieds_categories` WHERE `parent` = ?n ORDER BY `order`', $category_sid);
	}
	
	function moveBefore($category_sid, $dest_category_sid)
	{
		$category_order = $this->getOrder($category_sid);
		$dest_category_order = $this->getOrder($dest_category_sid);
		$parent_category_sid = $this->getParentSID($category_sid);
		
		if ($category_order < $dest_category_order)
		{
			\App()->DB->query('UPDATE `classifieds_categories` SET `order` = `order` - 1 WHERE `order` > ?n AND `order` < ?n AND `parent` = ?n', $category_order, $dest_category_order, $parent_category_sid);
			\App()->DB->query('UPDATE `classifieds_categories` SET `order` = ?n WHERE `sid` = ?n', $dest_category_order - 1, $category_sid);
		}
		else
		{
			\App()->DB->query('UPDATE `classifieds_categories` SET `order` = `order` + 1 WHERE `order` < ?n AND `order` >= ?n AND `parent` = ?n', $category_order, $dest_category_order, $parent_category_sid);
			\App()->DB->query('UPDATE `classifieds_categories` SET `order` = ?n WHERE `sid` = ?n', $dest_category_order, $category_sid);
		}
	}
	
	function moveAfter($category_sid, $dest_category_sid)
	{
		$category_order = $this->getOrder($category_sid);
		$dest_category_order = $this->getOrder($dest_category_sid);
		$parent_category_sid = $this->getParentSID($category_sid);
		
		if ($category_order < $dest_category_order)
		{
			\App()->DB->query('UPDATE `classifieds_categories` SET `order` = `order` - 1 WHERE `order` > ?n AND `order` <= ?n AND `parent` = ?n', $category_order, $dest_category_order, $parent_category_sid);
			\App()->DB->query('UPDATE `classifieds_categories` SET `order` = ?n WHERE sid = ?n', $dest_category_order, $category_sid);
		}
		else
		{
			\App()->DB->query('UPDATE `classifieds_categories` SET `order` = `order` + 1 WHERE `order` < ?n AND `order` > ?n AND `parent` = ?n', $category_order, $dest_category_order, $parent_category_sid);
			\App()->DB->query('UPDATE `classifieds_categories` SET `order` = ?n WHERE `sid` = ?n', $dest_category_order + 1, $category_sid);
		}
	}
	
	function setChildOf($category_sid, $dest_category_sid)
	{
		$parent_sid = $this->getParentSID($category_sid);
		$order = $this->getOrder($category_sid);
		\App()->DB->query('UPDATE `classifieds_categories` SET `order` = `order` - 1 WHERE `parent` = ?n AND `order` > ?n', $parent_sid, $order);
		
		$max_order = \App()->DB->getSingleValue('SELECT MAX(`order`) FROM `classifieds_categories` WHERE parent = ?n', $dest_category_sid);
		\App()->DB->query('UPDATE `classifieds_categories` SET `parent` = ?n, `order` = ?n WHERE `sid` = ?n', $dest_category_sid, $max_order + 1, $category_sid);
	}
	
	function &getTree()
	{
		if (empty($this->treeData))
		{
			$categories_data = \App()->DB->query('SELECT * FROM `classifieds_categories` ORDER BY `parent`, `order`');
			$treeBuilder = \App()->ObjectMother->createTreeBuilder();
			$treeBuilder->setData($categories_data);
			$treeBuilder->buildTree();
			$this->treeData = $treeBuilder->getTree();
		}
		return $this->treeData;
	}
	
	function getGrandParent($category_sid)
	{
		$grand_parent = null;
		
		$parent_sid = $this->getParentSID($category_sid);
		$grand_parent_sid = $this->getParentSID($parent_sid);
		if (!is_null($grand_parent_sid))
		{
			$query_result = \App()->DB->query('SELECT * FROM `classifieds_categories` WHERE `sid` = ?n', $grand_parent_sid);
			$grand_parent = array_pop($query_result);
		}
		return $grand_parent;
	}
	
	public function getCategoryParentTreeBySID($category_sid)
	{
		$result = array();
		for ($x = \App()->CategoryTree[$category_sid]; !is_null($x) ;$x = $x->getParent()) $result[] = $x->getId();
		return $result;
	}
	
	public function getCategory($category_info)
	{
		$category = new Category();
		$category->setListingsTypeDetails($this->getCategoryDetails($category_info));
		if (isset($category_info['sid']))
		{
			$category->setSID($category_info['sid']);
		}
		$category->setListingsTypeDetails($this->getCategoryDetails($category_info));
		return $category;
	}
	public function getCategoryDetails($category_info)
	{
		$details = new CategoryDetails();
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildPropertiesWithData($category_info);
		return $details;
	}
	public function getCategoryBrowseFields($categorySid)
	{
		$category = $this->getCategory($this->getInfoBySID($categorySid));
		return $category->getPropertyValue('browsing_settings');
	}
    public function getInheritedExtraParameter($categorySid, $extraParameterId)
    {
		$categoriesTree = $this->getTreeWithData();
		$category = $categoriesTree->getItem($categorySid);
		do
		{
			$extraParameter = $category->getExtraParameter($extraParameterId);
		} while(empty($extraParameter) && !is_null($category = $category->getParent()));
		return $extraParameter;
    }

	private function getInheritedExtraParameterForFile($categorySid, $extraParameterId, $filePath)
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$categoriesTree = $this->getTreeWithData();
		$category = $categoriesTree->getItem($categorySid);
		do
		{
			$extraParameter = $category->getExtraParameter($extraParameterId);
		}
		while ((empty($extraParameter) || !$templateProcessor->templateExists($filePath . $extraParameter)) && !is_null($category = $category->getParent()));

		return $filePath . $extraParameter;
	}

	private function _getInheritedLastModifiedForExtraParameter($categorySid, $extraParameterId)
    {
		$categoriesTree = $this->getTreeWithData();
		$category = $categoriesTree->getItem($categorySid);
		do
		{
			$extraParameter = $category->getExtraParameter($extraParameterId);
			$lastModified = $category->getExtraParameter('last_modified');
		} while(empty($extraParameter) && !is_null($category = $category->getParent()));
		return $lastModified;
    }
	public function getListingTemplateContentForStringRepresentation($categorySid)
	{
		return $this->getInheritedExtraParameter($categorySid, 'listing_caption_template_content');
	}
	public function getListingTemplateLastModifiedForStringRepresentation($categorySid)
	{
        return $this->_getInheritedLastModifiedForExtraParameter($categorySid, 'listing_caption_template_content');
	}
	public function getListingTemplateContentForUrlSeoData($categorySid)
	{
		return $this->getInheritedExtraParameter($categorySid, 'listing_url_seo_data');
	}
	public function getListingTemplateLastModifiedForUrlSeoData($categorySid)
	{
        return $this->_getInheritedLastModifiedForExtraParameter($categorySid, 'listing_url_seo_data');
	}
	public function getCategoryInputTemplateFileName($categorySid)
	{
		return $this->getInheritedExtraParameterForFile($categorySid, 'input_template', 'category_templates/input/');
	}
	public function getCategorySearchTemplateFileName($categorySid)
	{
		return $this->getInheritedExtraParameterForFile($categorySid, 'search_template', 'category_templates/search/');
	}
	public function getCategoryRefineSearchTemplateFileName($categorySid)
	{
		return $this->getInheritedExtraParameterForFile($categorySid, 'refine_search_template', 'category_templates/search/');
	}
	public function getCategorySearchResultTemplateFileName($categorySid)
	{
		return $this->getInheritedExtraParameterForFile($categorySid, 'search_result_template', 'category_templates/display/');
	}
	public function getCategoryViewTemplateFileName($categorySid)
	{
		return $this->getInheritedExtraParameterForFile($categorySid, 'view_template', 'category_templates/display/');
	}

	private $categoriesTreeWithData;
	public function getTreeWithData()
	{
		if (is_null($this->categoriesTreeWithData))
		{
			$categoriesData = \App()->DB->query('SELECT * FROM `classifieds_categories`');
			$categoriesTree = $this->getTree();

			$categoryDetails = new CategoryDetails();
			$categoryDetailIds = [];
            $categoryDetailsInfo = $categoryDetails->getDetailsInfo();
            foreach($categoryDetailsInfo as &$propertyInfo)
            {
                $categoryDetailIds[] = $propertyInfo['id'];
            }

            $categoryDetailIds[] = 'last_modified';
			foreach ($categoryDetailIds as &$detailId)
			{
				$categoriesTree->addScalarExtraParameters($categoriesData, 'sid', $detailId, $detailId);
			}
			$this->categoriesTreeWithData = $categoriesTree;
		}
		return $this->categoriesTreeWithData;
		
	}

    public function alterPropertiesForRootCategoryNode($category)
    {
        $category->dontSaveProperty('parent');

        $category->getDetails()->makePropertyRequired('input_template');
        $category->getDetails()->makePropertyRequired('search_template');
        $category->getDetails()->makePropertyRequired('search_result_template');
        $category->getDetails()->makePropertyRequired('view_template');
        
        $category->getDetails()->makePropertyRequired('listing_caption_template_content');
        $category->getDetails()->makePropertyRequired('listing_url_seo_data');

        return $category;
    }

	public function getAllCategoriesCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_categories` WHERE id != 'root'");
	}

	public function getCategoryBranchSids($categorySid)
	{
		$tree = $this->getTree();
		$treeWalker = \App()->ObjectMother->createTreeWalker();
		$objectSidCollector = new \modules\field_types\lib\ObjectSidCollector();
		$treeWalker->setHandler($objectSidCollector);
		$treeWalker->walkDown($tree->getItem($categorySid));
		return $objectSidCollector->getObjectSids();

	}

	public function definePageMetaForCategory($categorySid)
	{
        if (\App()->Navigator->getUri() != '/')
        {
			$params['page_title'] = 'appendPageTitle';
			$params['meta_keywords'] = 'appendPageKeywords';
            $params['meta_description'] = 'appendPageDescription';
            foreach ($params as $paramName => $function)
            {
                $param = $this->getInheritedExtraParameter($categorySid,$paramName);
                $param = strip_tags($param);
                \App()->GlobalTemplateVariable->$function($param);
            }
        }
	}

    public function prepareBulkCategories($params)
    {
        $count = count($params['id']);
        $categories = array();
        $arr = array();
        for ($i=0; $i < $count; $i++) {
            $arr[$i]['parent'] = $params['parent'];
            $arr[$i]['id'] = $params['id'][$i];
            $arr[$i]['name'] = $params['name'][$i];
            $arr[$i]['meta_keywords'] = $params['meta_keywords'][$i];
            $arr[$i]['meta_description'] = $params['meta_description'][$i];
            $arr[$i]['page_title'] = $params['page_title'][$i];

            $categories[$i] = App()->CategoryManager->getCategory($arr[$i]);
        }

        return $categories;
    }

    public function saveCategoryBunch($bunch)
    {
        $count = count($bunch);

        for($i=0;$i<$count;$i++) {
            $add_category_form = new \lib\Forms\Form($bunch[$i]);
            if ($add_category_form->isDataValid()){
                $this->saveCategory($bunch[$i]);
            }
        }
    }

	public function getBranchesSids($categoryId)
	{
		$categorySid = $this->getCategorySIDByID($categoryId);
		return  \App()->CategoryTree->getCategoryBranches($categorySid);
	}

}

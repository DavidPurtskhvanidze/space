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


namespace modules\classifieds\lib\Browse;

class BrowseManager
{
	private $root_category_sid;
	private $root_category_id;
	private $category_sid;
	private $numberOfLevels;
	private $items;
	private $requestData;
	private $userSid = null;
	/** @var Listing */
	private $listing;
	public $params;
	public $schema;
	public $tree_memory_fields;
	/**
	 * @var CategorySearcherFactory
	 */
	public $searcherFactory;

	public function setRootCategoryId($root_category_id)
	{
		$this->root_category_id = $root_category_id;
	}

	public function setNumberOfLevels($numberOfLevels)
	{
		$this->numberOfLevels = $numberOfLevels;
	}

	public function setUserSid($userSid)
	{
		$this->userSid = $userSid;
	}

	public function __construct($category_id, $userSid)
	{
		$this->setRootCategoryId($category_id);
		$this->setUserSid($userSid);
	}

	function init()
	{
		$this->root_category_sid = \App()->CategoryManager->getCategorySIDByID($this->root_category_id);
		$this->category_sid = $this->root_category_sid;

        if (is_null($this->category_sid)) {
			trigger_error("Can't set filter by category for unknown category: '" . $this->category_id . "'.", E_USER_WARNING);
		}

		$this->searcherFactory = \App()->ObjectMother->createCategorySearcherFactory();
		$this->defineMetadata($this->getFieldsIds());
	}

	private function defineMetadata($fieldsIds)
	{
		if (!empty($this->numberOfLevels) || (is_numeric($this->numberOfLevels) && $this->numberOfLevels == 0)) $fieldsIds = array_slice($fieldsIds, 0, $this->numberOfLevels);
		$fieldsIds = $this->changeCategorySidToCategorySid($fieldsIds);
		$this->listing = $this->createListing();
		$this->schema = $this->_createSchema($fieldsIds);
		$this->params = $this->_createParams();
		$this->requestData = $this->_getRequestData();
		$categorySid = $this->getCategorySidFromRequest($this->requestData);
		if ($categorySid != $this->category_sid) {
			if (!empty($this->numberOfLevels) && $this->numberOfLevels <= count($fieldsIds)) return;
			$this->category_sid = $categorySid;
			$this->defineMetadata(array_merge($fieldsIds, \App()->CategoryManager->getCategoryBrowseFields($categorySid)));
		}
	}

	public function setCurrnetLevelAsLastLevel()
	{
		$currentLevel = $this->getLevel();
		$this->numberOfLevels = $currentLevel;
		$this->defineMetadata($this->getFieldsIds());
	}

	private function changeCategorySidToCategorySid($fieldsIds)
	{
		return array_map(create_function('$f', 'return $f == "category_sid" ? "category_sid" : $f;'), $fieldsIds);
	}

	public function getFieldsIds()
	{
		if (!isset ($_REQUEST['fields']))
			return [];
		$fieldsIds = explode(",", $_REQUEST['fields']);
		$fieldsIds = array_map("trim", $fieldsIds);
		$fieldsIds = array_filter($fieldsIds, create_function('$v', 'return !empty($v);'));
		return $fieldsIds;
	}

	private function getCategorySidFromRequest($request)
	{
		return end($request['category_sid']['tree']);
	}

	function createListing()
	{
		return \App()->ObjectMother->getListingFactory()->getListing([], $this->category_sid);
	}

	function getListing()
	{
		return $this->listing;
	}

	function _createParams()
	{
		$params = \App()->UrlParamProvider->getParams();
		$params = array_slice($params, 0, count($this->schema));
		return $params;
	}

	function getParams()
	{
		return $this->params;
	}

	function canBrowse()
	{
		return $this->getLevel() <= $this->_getMaxLevel();
	}

	function getRequestDataForSearchResults()
	{
		return $this->requestData;
	}


	function getItems()
	{
		if ($this->getLevel() > $this->_getMaxLevel()) {
			trigger_error("Requested browse level is more than max level", 256);
			return;
		}
		if (is_null($this->items)) {
			$categorySearcher = $this->searcherFactory->getCategorySearcher($this->_getField(), $this->category_sid);
			$categorySearcher->setListing($this->getListing());
			$items = $categorySearcher->getItems($this->requestData);
			$this->_putUrl($items);
			$this->_putPropertyDomain($items);
			$this->items = $items;
		}
		return $this->items;
	}

	public function getTotalListingsNumber()
	{
		return \lib\ORM\SearchEngine\Search::createSearch($this->requestData, $this->getListing())->getNumberOfObjectsFound();
	}

	function _putUrl(&$items)
	{
		foreach ($items as $index => $item) {
			if (!isset($items[$index]['url'])) {
				$seo_friendly = preg_replace("/ /", "+", urlencode(str_replace('/', '&#47;', $item['caption'])));
				$items[$index]['url'] = $seo_friendly;
			}
		}
	}

	function _putPropertyDomain(&$items)
	{
		foreach ($items as $index => $item) {
			if (!isset($items[$index]['propertyDomain'])) {
				$items[$index]['propertyDomain'] = 'Property_' . $this->getFieldID();
			}
		}
	}

	function _createSchema($fieldsIds)
	{
		$this->tree_memory_fields = [];
		$res = [];
		foreach ($fieldsIds as $field) {
			$property = $this->getListing()->getProperty($field);
			if (empty($property)) {
				throw new BrowsingException_InvalidField($field);
			}

			$type = $property->getType();
			$res[] = array
			(
				'field' => $field,
				'treeLevel' => $this->_getTreeLevel($type, $field),
				'type' => $type,
				'sid' => $property->getSID()
			);
		}
		return $res;
	}

	function _getTreeLevel($type, $field)
	{
		$res = 0;
		if ($type == 'tree') {
			if (!isset($this->tree_memory_fields[$field])) {
				if ($field == 'category_sid')
					$this->tree_memory_fields[$field] = \App()->CategoryTree[$this->root_category_sid]['level'];
				else
					$this->tree_memory_fields[$field] = 0;
			}
			$this->tree_memory_fields[$field]++;
			$res = $this->tree_memory_fields[$field];
		}
		return $res;
	}

	private function getCriterionForCategory($sid)
	{
		return \App()->CategoryTree->getIdsOfParentsFor($sid);

	}

	function _getRequestdata()
	{
		$res = [];
		$res['category_sid']['tree'] = $this->getCriterionForCategory($this->category_sid);
		$level = $this->getLevel();
		for ($i = 0; $i < $level; $i++) {
			$value = $this->_getValue($i);
			$filterItem = $this->schema[$i];
			$field = $filterItem['field'];
			if ($field == 'category_sid') {
				$parent_sid = $res[$field]['tree'][$filterItem['treeLevel'] - 1];
				$sid = \App()->CategoryTree->getSidByParentAndName($parent_sid, $value);
				$res[$field]['tree'] = $this->getCriterionForCategory($sid);
			} else {
				switch ($filterItem['type']) {
					case 'tree' :
						$parent_sids = $this->_get_parent_sids_from_request_data($res, $field, $filterItem['treeLevel']);
						$sid = \App()->ListingFieldTreeManager->getSIDByCaption($filterItem['sid'], $parent_sids, $value);
						$res[$field]['tree'][$filterItem['treeLevel'] - 1] = $sid;
						break;
					case 'list' :
						$listItemManager = \App()->ObjectMother->createListingFieldListItemManager();
						$listItem = $listItemManager->getListItemByValue($filterItem['sid'], $value);
						$res[$field]['equal'] = $listItem->getSid();
						break;
					case 'string' :
					case 'integer' :
					case 'geo' :
						$res[$field]['equal'] = $value;
						break;
				}
			}
		}
		$res['active']['equal'] = 1;
		if ($this->userSid)
			$res['user_sid']['equal'] = $this->userSid;
		return $res;
	}

	function _get_parent_sids_from_request_data($request_data, $field, $treeLevel)
	{
		$parents = [];
		for ($i = 0; $i < $treeLevel - 1; $i++) $parents[$i] = isset($request_data[$field]['tree'][$i]) ? $request_data[$field]['tree'][$i] : 0;
		return $parents;
	}

	function _getField()
	{
		return isset($this->schema[$this->getLevel()]) ? $this->schema[$this->getLevel()] : [];
	}

	function getFieldID()
	{
		$field = $this->_getField();
		return isset($field['field']) ? $field['field'] : null;
	}

	function _getFieldByLevel($level)
	{
		return isset($this->schema[$level]) ? $this->schema[$level] : [];
	}

	function _getValue($i)
	{
		$params = $this->getParams();
		return $params[$i];
	}

	function getLevel()
	{
		return count($this->getParams());
	}

	function _getMaxLevel()
	{
		return count($this->schema) - 1;
	}

	function getNavigationElements($page_uri)
	{
		$elements = [];

		foreach ($this->params as $level => $param) {
			$field = $this->_getFieldByLevel($level);
			$metadata = $this->_getMetaDataByFieldData($field);

			$page_uri = \App()->Path->combineURL($page_uri, str_replace("/", '%252F', $param));
			$page_uri = str_replace(" ", "+", $page_uri);
			$element = array('caption' => $param, 'uri' => $page_uri, 'metadata' => $metadata);
			$elements[] = $element;
		}
		return $elements;
	}

	function getBrowsingMetaData()
	{
		$field = $this->_getField();
		$metadata = $this->_getMetaDataByFieldData($field);

		return array
		(
			'browseItem' => array
			(
				'caption' => $metadata,
			),
		);
	}

	function _getMetaDataByFieldData($field)
	{
		$metadata = null;

		if ($field['type'] == 'list' || $field['type'] == 'tree') {
			$metadata['domain'] = 'Property_' . $field['field'];
		} else {
			$metadata['type'] = $field['type'];
		}

		return $metadata;
	}
}


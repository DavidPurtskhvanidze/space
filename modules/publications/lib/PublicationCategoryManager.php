<?php
/**
 *
 *    Module: publications v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: publications-7.5.0-1
 *    Tag: tags/7.5.0-1@19806, 2016-06-17 13:20:27
 *
 *    This file is part of the 'publications' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\publications\lib;

class PublicationCategoryManager extends \lib\ORM\ObjectManager implements \core\IService
{
	protected $tableName = 'publications_categories';

	/**
	 * @param PublicationCategory $object
	 */
	public function saveObject($object)
	{
		$order = $object->getOrder();
		if(empty($order))
		{
			$object->setOrder($this->getPublicationCategoriesMaxOrder() + 1);
		}
		return parent::saveObject($object);
	}

	public function createPublicationCategory($info = array())
	{
		$object = new PublicationCategory();
		$object->defineDetails($info);
		if (!empty($info['sid']))
		{
			$object->setSID($info['sid']);
		}
		return $object;
	}

	public function getCollectionForTemplate()
	{
		$rowMapper = new RowToPublicationCategoryAdapter();
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setDB(\App()->DB);
		$m = $this->createPublicationCategory();
		$search->setModelObject($m);
		$search->setObjectsPerPage(100);
		$search->setRowMapper($rowMapper);
		$search->setSortingFields(array('order' => 'ASC'));
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}

	public function deletePublicationCategory($category_sid)
	{
		return $this->deleteObject($this->tableName, $category_sid);
	}

	public function setPublicationCategoriesOrder($sortingOrder)
	{
		$order = 1;
		foreach ($sortingOrder as $sid)
		{
			\App()->DB->query("UPDATE `{$this->tableName}` set `order` = ?n WHERE sid = ?n", $order++, $sid);
		}
	}

	private function getPublicationCategoriesMaxOrder()
	{
		return \App()->DB->getSingleValue("SELECT MAX(`order`) FROM {$this->tableName}");
	}

	public function getObjectBySid($objectSid)
	{
		$info = $this->getObjectInfoBySID($this->tableName, $objectSid);
		if (is_null($info)) return null;
		return $this->createPublicationCategory($info);
	}

	private $publicationCategoriesAsListValues;

	public function getCollectionForListValues()
	{
		if (is_null($this->publicationCategoriesAsListValues))
		{
			$rowMapper = new RowToPublicationCategoryAsListValuesItem();
			$search = new \lib\ORM\SearchEngine\Search();
			$search->setDB(\App()->DB);
			$search->setModelObject($this->createPublicationCategory());
			$search->setObjectsPerPage(100);
			$search->setRowMapper($rowMapper);
			$search->setSortingFields(array('order' => 'ASC'));
			$this->publicationCategoriesAsListValues = $search->getFoundObjectCollection();
		}
		return $this->publicationCategoriesAsListValues;
	}

	public function getCategorySidById($categoryId)
	{
		return \App()->DB->getSingleValue("SELECT `sid` FROM `{$this->tableName}` WHERE `id` = ?s", $categoryId);
	}

}

class RowToPublicationCategoryAdapter
{
	public function mapRowToObject($row)
	{
		return \App()->PublicationCategoryManager->createPublicationCategory($row);
	}
}

class RowToPublicationCategoryAsListValuesItem
{
	public function mapRowToObject($row)
	{
		return array
		(
			'id' => $row['sid'],
			'caption' => $row['title'],
		);
	}
}

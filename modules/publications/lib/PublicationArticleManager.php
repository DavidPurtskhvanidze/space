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

class PublicationArticleManager extends \lib\ORM\ObjectManager implements \core\IService
{
	protected $tableName = 'publications_articles';

	public function getCollectionForTemplate($categorySid, $sortingField = null, $sortingOrder = null, $limit = 100)
	{
		$rowMapper = new RowToPublicationArticleAdapter();
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setDB(\App()->DB);
		$search->setModelObject($this->createPublicationArticle());
		$search->setObjectsPerPage($limit);
		$search->setRowMapper($rowMapper);
		$search->setRequest(array('category_sid' => array('equal' => $categorySid)));
		if (!empty($sortingField) && !empty($sortingOrder))
		{
			$search->setSortingFields(array($sortingField => $sortingOrder));
		}
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}

	/**
	 * @param array $info
	 * @return PublicationArticle
	 */
	public function createPublicationArticle($info = array())
	{
		$object = new PublicationArticle();
		$object->defineDetails($info);
		if (!empty($info['sid']))
		{
			$object->setSID($info['sid']);
            $object->addProperty(array(
                'id' => 'picture_url',
                'caption' => 'Picture',
                'type' => 'url',
                'is_required' => false,
                'value' => $info['picture_url'],
            ));
		}
		return $object;
	}

	public function deletePublicationArticle($article_sid)
	{
		return $this->deleteObject($this->tableName, $article_sid);
	}

	/**
	 * @param $objectSid
	 * @return PublicationArticle
	 */
	public function getObjectBySid($objectSid)
	{
		$info = $this->getObjectInfoBySID($this->tableName, $objectSid);
		if (is_null($info)) return null;
		return $this->createPublicationArticle($info);
	}

	public function getArticleInfoBySID($sid)
	{
		$articleSID = \App()->DB->getSingleValue("SELECT sid FROM `publications_articles` WHERE `sid` = ?s", $sid);
		return is_null($articleSID) ? null : \App()->ObjectDBManager->getObjectInfo("publications_articles", $sid);
	}
}

class RowToPublicationArticleAdapter
{
	public function mapRowToObject($row)
	{
		return \App()->PublicationArticleManager->createPublicationArticle($row);
	}
}

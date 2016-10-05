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

class PublicationArticle extends \lib\ORM\Object
{
	protected $tableName = 'publications_articles';

	public function defineDetails($info)
	{
		$this->setDetails($this->getPublicationArticleDetails($info));
	}

	public function getPublicationArticleDetails($info)
	{
		$details = new \lib\ORM\ObjectDetails();
		$details->setDetailsInfo($this->getDetailsInfo());
		$details->setTableName($this->tableName);
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildPropertiesWithData($info);
		return $details;
	}

	private function getDetailsInfo()
	{
		return array
		(
			array
			(
				'id' => 'title',
				'caption' => 'Title',
				'type' => 'string',
				'is_required' => true,
			),
			array
			(
				'id' => 'description',
				'caption' => 'Article Brief',
				'type' => 'text',
                'escape' => false,
				'is_required' => false,
			),
			array
			(
				'id' => 'text',
				'caption' => 'Full Article',
				'type' => 'text',
                'escape' => false,
				'is_required' => false,
			),
			array
			(
				'id' => 'date',
				'caption' => 'Date',
				'type' => 'datetime',
				'is_required' => false,
			),
			array
			(
				'id' => 'category_sid',
				'caption' => 'Category',
				'type' => 'list',
				'is_required' => false,
				'list_values' => \App()->PublicationCategoryManager->getCollectionForListValues()
			),
			array(
				'id' => 'picture',
				'caption' => 'Picture',
				'type' => 'picture',
                'styles' => [
                    'thumb' => \App()->SettingsFromDB->getSettingByName('article_picture_width') . 'x' . \App()->SettingsFromDB->getSettingByName('article_picture_height') . '#',
                ],
				'is_required' => false,
			)
		);
	}

	public function getCategorySid()
	{
		return $this->getPropertyValue('category_sid');
	}

	public function setCategorySid($category_sid)
	{
		return $this->setPropertyValue('category_sid', $category_sid);
	}

	public function addCategoryProperty()
	{
		$this->addProperty(array(
			'id' => 'category',
			'caption' => 'Category',
			'type' => 'object',
			'value' => \App()->PublicationCategoryManager->getObjectBySid($this->getCategorySid()),
		));
	}
}

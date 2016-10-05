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

class PublicationCategory extends \lib\ORM\Object
{
	protected $tableName = 'publications_categories';

	public function defineDetails($info)
	{
		$this->setDetails($this->getPublicationCategoryDetails($info));
	}

	public function getPublicationCategoryDetails($info)
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
				'id' => 'id',
				'caption' => 'ID',
				'type' => 'unique_string',
				'is_required' => true,
			),
			array
			(
				'id' => 'title',
				'caption' => 'Title',
				'type' => 'string',
				'is_required' => true,
			),
			array
			(
				'id' => 'order',
				'caption' => 'Order',
				'type' => 'integer',
			),
		);
	}

	public function getOrder()
	{
		return $this->getPropertyValue('order');
	}

	public function setOrder($v)
	{
		return $this->setPropertyValue('order', $v);
	}
}

<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\FrontEnd;

class AfterObjectRated implements \lib\ORM\Rating\IAfterObjectRated
{
	private $_ratingManager;
	private $_objectSid;
	private $_fieldSid;
	private $_userSid;
	
	public function setRatingManager($ratingManager)
	{
		$this->_ratingManager = $ratingManager;
	}
	public function setObjectSid($objectSid)
	{
		$this->_objectSid = $objectSid;
	}
	public function setFieldSid($fieldSid)
	{
		$this->_fieldSid = $fieldSid;
	}
	public function setUserSid($userSid)
	{
		$this->_userSid = $userSid;
	}
	public function perform()
	{
		$objectType = $this->_ratingManager->getObjectType();
		$ratingData = $this->_ratingManager->calculateRating($this->_objectSid, $this->_fieldSid);
		
		$tableName = $this->_getTableNameByObjectType($objectType);
		$columnName = $this->_getColumnNameByFieldSidAndObjectType($this->_fieldSid, $objectType);
		
		\App()->DB->query("UPDATE `{$tableName}` SET `{$columnName}`=?s WHERE `sid`=?s", $ratingData['rating'] . '|'  . $ratingData['count'], $this->_objectSid);
	}
	
	private function _getTableNameByObjectType($objectType)
	{
		switch ($objectType) {
			case 'listing':
				return 'classifieds_listings';
			default:
				throw new \Exception('Unkown ObjectType: ' . $objectType);
		}
	}
	private function _getColumnNameByFieldSidAndObjectType($fieldSid, $objectType)
	{
		$objectProperty = NULL;
		
		switch ($objectType) {
			case 'listing':
				$objectProperty = \App()->OrmObjectFactory->createObjectProperty(\App()->ListingFieldManager->getInfoBySID($fieldSid));
			break;
			default:
				throw new \Exception('Unkown ObjectType: ' . $objectType);
		}

		return $objectProperty->getColumnName();
	}
}

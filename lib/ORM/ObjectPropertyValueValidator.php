<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM;

/**
 * Class for property validators
 *
 * This class is designed to develop validators for object property value
 */
abstract class ObjectPropertyValueValidator
{
	protected $errorCode = null;
	protected $extraParameters = array();
	protected $errorTemplateModule = null;

	/**
	 * Validates value of property
	 *
	 * @abstract
	 * @param $value mixed value to validate
	 * @param integer $propertyId
	 * @param Object $object
	 * @return boolean true if validation succeeded false otherwise
	 */
	abstract public function isValid($value, $propertyId, $object);

	/**
	 * get error code
	 *
	 * returns error code of the validator
	 * @return string
	 */
	public function getErrorCode()
	{
		return $this->errorCode;
	}

	/**
	 * get extra parameters for error
	 *
	 * returns extra information for error
	 * @return array
	 */
	public function getExtraParameters()
	{
		return $this->extraParameters;
	}

	public function getErrorTemplateModule()
	{
		return $this->errorTemplateModule;
	}
}

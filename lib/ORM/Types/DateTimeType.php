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


namespace lib\ORM\Types;

class DateTimeType extends Type
{
	const ISO_DATE_TIME_RE = '/^(\\d{4})\\D?(0[1-9]|1[0-2])\\D?([12]\\d|0[1-9]|3[01])(\\D?([01]\\d|2[0-3])\\D?([0-5]\\d)\\D?([0-5]\\d)?\\D?(\\d{3})?)?$/';

	public function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->sql_type = 'DATETIME';
		$this->default_template = 'date.tpl';
	}

	public function isValid()
	{
		$value = $this->property_info['value'];
		if (!\App()->I18N->isValidDateTime($value))
		{
			$this->addValidationError('WRONG_DATE_FORMAT', array('currentLanguageDateFormat' => \App()->I18N->getDateFormat()));
			return false;
		}
		return true;
	}

	public function getSQLValue()
	{
		if (empty($this->property_info['value'])) return null;
		if ($this->valueIsInSqlFormat()) return "'" . $this->property_info['value'] . "'";
		$i18n = \App()->I18N;
		$date = $i18n->getInput('datetime', $this->property_info['value']);
		return "'$date'";
	}

	public function getKeywordValue()
	{
		return \App()->I18N->getInput('datetime', $this->property_info['value']);
	}

	public function getColumnDefinition()
	{
		return 'DATETIME';
	}

	private function valueIsInSqlFormat()
	{
		if (preg_match(self::ISO_DATE_TIME_RE, $this->property_info['value']))
		{
			return true;
		}
		return false;
	}
}

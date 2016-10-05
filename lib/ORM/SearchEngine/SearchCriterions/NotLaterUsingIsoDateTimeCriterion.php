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


namespace lib\ORM\SearchEngine\SearchCriterions;

class NotLaterUsingIsoDateTimeCriterion extends AbstractIsoDateTimeCriterion
{
	var $comparisson_sign = '<=';

	function setValue($value)
	{
		$this->value = $value;
		if (preg_match("/^(19|20)[0-9]{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/", $this->value) > 0)
		{
			$this->value .= ' 23:59:59';
		}
	}
}

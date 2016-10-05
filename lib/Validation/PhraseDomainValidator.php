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


namespace lib\Validation;
class PhraseDomainValidator
{
	function isValid($value)
	{
		if (!preg_match('/^[a-z0-9_]{1,255}$/iu', $value))
		{
			return false;
		}

		if ($value[0] == '_')
		{
			return false;
		}

		return true;
	}
}

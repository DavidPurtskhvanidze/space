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

class IncludesAnyCriterion extends IncludesAllCriterion
{
	public function getSystemSQL()
	{
		if(!$this->isValid()) return null;
		return '(' . join(" OR ", $this->getCriteria()) . ')';
	}
}

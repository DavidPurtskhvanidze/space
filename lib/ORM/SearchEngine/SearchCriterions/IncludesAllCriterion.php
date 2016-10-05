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

class IncludesAllCriterion extends SearchCriterion
{
	public function getSystemSQL()
	{
		if(!$this->isValid()) return null;
		return join(" AND ", $this->getCriteria());
	}
	public function isValid()
	{
		$v = trim($this->value);
		return !empty($v);
	}
	protected function getCriteria()
	{
		$parts = $this->getParts();
		$criteria = array();
		foreach ($parts as $part)
		{
			$criteria[] = "{$this->property->getFullColumnName()} LIKE '%{$part}%'";
		}
		return $criteria;
	}
	protected function getParts()
	{
		$parts = explode(" ", $this->value);
		$parts = array_filter($parts, create_function('$v', 'return !empty($v);'));
		$parts = array_map("trim", $parts);
		$parts = array_map(array($this->stringEscaper, 'escapeString'), $parts);
		$parts = array_map(array($this, 'escapeLikeWildcardChars'), $parts);
		return $parts;
	}
	protected function escapeLikeWildcardChars($v)
	{
		return addcslashes($v, "%_");
	}
}

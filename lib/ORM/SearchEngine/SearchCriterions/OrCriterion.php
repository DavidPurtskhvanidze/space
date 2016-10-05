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

/**
 * Criterion OR
 *
 * OR criterion. It only joins other criteria with "OR".
 * Structure of $this->value is the following:
 * array ( $criteria1Data, $criteria2Data ) );
 * for example:
 * array ( array ( 'like' => 'demo' ), array ( 'like' => 'test' ));
 * URI Examples:
 * 1. users with name like 'demo' or 'test'
 *   username[or][][like]=demo&username[or][][like]=test
 * 2. listing with price more then 100 or equal to 20 or equal to 50
 *   Price[or][][more]=100&Price[or][][equal]=20&Price[or][][equal]=50
 */
class OrCriterion extends SearchCriterion
{
	private $criteria = array();

	public function getSystemSQL()
	{
		$criteria = $this->getCriteria();
		$sqlParts = array();
		foreach ($criteria as $criterion)
		{
			$sqlParts[] = $criterion->getSystemSQL();
		}
		$sql = "(" . join(" OR ", $sqlParts) . ")";
		return $sql;
	}

	public function isValid()
	{
		$criteria = $this->getCriteria();
		foreach ($criteria as $criterion)
		{
			if (!$criterion->isValid())
			{
				return false;
			}
		}
		return true;
	}

	private function getCriteria()
	{
		if (empty($this->criteria))
		{
			foreach ($this->value as $criterionBlock)
			{
				foreach ($criterionBlock as $type => $value)
				{
					$criterion = \App()->SearchCriterionFactory->getCriterionByType($type);
					$criterion->setValue($value);
					$criterion->setProperty($this->property);
					$this->criteria[] = $criterion;
				}
			}
		}
		return $this->criteria;
	}
}

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


namespace lib\ORM\SearchEngine;

class SearchCriterionFactory implements \core\IService
{
	public function init(){}

	function getCriterionByType($type)
	{
		$type=strtolower($type);
		if(!isset(self::$CRITERIA_TYPES[$type])) throw new \Exception("Criterion of unknown type \"$type\" requested");
		$criterion = new self::$CRITERIA_TYPES[$type]($type);
		$criterion->setStringEscaper($this);
		$criterion->setI18N(\App()->I18N);
		$criterion->setRadiusSearchUnit(\App()->SettingsFromDB->getSettingByName('radius_search_unit'));
		return $criterion;
	}

	public function escapeString($string)
	{
		return \App()->DB->real_escape_string($string);
	}

	private static $CRITERIA_TYPES=array(
										   'equal'			=>	'\lib\ORM\SearchEngine\SearchCriterions\EqualCriterion',
										   'not_equal'		=>	'\lib\ORM\SearchEngine\SearchCriterions\NotEqualCriterion',
										   'like'			=>	'\lib\ORM\SearchEngine\SearchCriterions\LikeCriterion',
										   'in'				=>	'\lib\ORM\SearchEngine\SearchCriterions\InCriterion',
										   'not_in'			=>	'\lib\ORM\SearchEngine\SearchCriterions\NotInCriterion',
										   'more'			=>	'\lib\ORM\SearchEngine\SearchCriterions\MoreCriterion',
										   'less'			=>	'\lib\ORM\SearchEngine\SearchCriterions\LessCriterion',
										   'not_more'		=>	'\lib\ORM\SearchEngine\SearchCriterions\LessEqualCriterion',
										   'not_less'		=>	'\lib\ORM\SearchEngine\SearchCriterions\MoreEqualCriterion',
										   'geo'			=>	'\lib\ORM\SearchEngine\SearchCriterions\GeoCriterion',
                                           'map'			=>	'\lib\ORM\SearchEngine\SearchCriterions\MapCriterion',
										   'not_empty'		=>	'\lib\ORM\SearchEngine\SearchCriterions\NotEmptyCriterion',
										   'tree'			=>	'\lib\ORM\SearchEngine\SearchCriterions\TreeCriterion',
										   'tree_in'		=>	'\lib\ORM\SearchEngine\SearchCriterions\TreeInCriterion',
										   'multilist'		=>	'\lib\ORM\SearchEngine\SearchCriterions\MultiListCriterion',
										   'not_earlier'	=>	'\lib\ORM\SearchEngine\SearchCriterions\NotEarlierCriterion',
										   'not_later'		=>	'\lib\ORM\SearchEngine\SearchCriterions\NotLaterCriterion',
										   'not_earlier_using_iso_date_time' => '\lib\ORM\SearchEngine\SearchCriterions\NotEarlierUsingIsoDateTimeCriterion',
										   'not_later_using_iso_date_time' => '\lib\ORM\SearchEngine\SearchCriterions\NotLaterUsingIsoDateTimeCriterion',
										   'includes_all' => '\lib\ORM\SearchEngine\SearchCriterions\IncludesAllCriterion',
										   'includes_any' => '\lib\ORM\SearchEngine\SearchCriterions\IncludesAnyCriterion',
										   'is_not_null'	=>	'\lib\ORM\SearchEngine\SearchCriterions\IsNotNullCriterion',
										   'is_null'		=>	'\lib\ORM\SearchEngine\SearchCriterions\IsNullCriterion',
										   'or' => '\lib\ORM\SearchEngine\SearchCriterions\OrCriterion',
										);
}

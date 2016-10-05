<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib;

class ImportMultiListDataOutputDatasource extends ImportListDataOutputDatasource
{
	private $importConfig;

	public function __construct($config)
	{
		$this->importConfig = $config;
	}

	function canAdd($fieldInfo)
	{
		if (!parent::canAdd($fieldInfo))
		{
			return false;
		}

		$listItemCount = count($this->listDataManager->getHashedMultiListItemsByFieldSID($this->importConfig->getFieldSid()));
		if ($listItemCount >= $this->importConfig->getMaxItems())
		{
			throw new MultiListValuesLimitExceededException();
		}
		return true;
	}
}

class MultiListValuesLimitExceededException extends \Exception
{
}

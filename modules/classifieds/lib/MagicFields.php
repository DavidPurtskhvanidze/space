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


class MagicFields extends \ArrayIterator
{
	private $formFieldsInfo;

	public function __construct($array = array(), $flags = 0)
	{
		parent::__construct($array, $flags);
		$this->formFieldsInfo = $array;
	}

	public function filterByType($type)
	{
		$formFieldsInfo = array_filter($this->formFieldsInfo, function ($formFieldInfo) use ($type)
		{
			return $formFieldInfo['type'] == $type;
		});
		return new MagicFields($formFieldsInfo);
	}

	public function excludeSystemFields()
	{
		$fieldsToRemove = array('user', 'activation_date', 'user_sid', 'meta_keywords', 'meta_description', 'page_title', 'type', 'category', 'expiration_date', 'active', 'sid', 'moderation_status', 'views', 'package', 'pictures', 'username', 'keywords', 'numberOfComments', 'listing_package', 'category_sid', 'feature_youtube_video_id');
		$fieldsToRemove = array_merge($fieldsToRemove, \App()->ListingFeaturesManager->getAllFeatureIds());
		$formFieldsInfo = array_diff_key($this->formFieldsInfo, array_flip($fieldsToRemove));
		return new MagicFields($formFieldsInfo);
	}

	public function excludeByType()
	{
		$types = func_get_args();
		$typesInKey = array_flip($types);

		// isset is faster then in_array
		$formFieldsInfo = array_filter($this->formFieldsInfo, function ($formFieldInfo) use ($typesInKey)
		{
			return !isset($typesInKey[$formFieldInfo['type']]);
		});

		return new MagicFields($formFieldsInfo);
	}
}

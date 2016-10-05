<?php
/**
 *
 *    Module: listing_feature_slideshow v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_slideshow-7.5.0-1
 *    Tag: tags/7.5.0-1@19792, 2016-06-17 13:19:49
 *
 *    This file is part of the 'listing_feature_slideshow' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_slideshow;

class ListingPackageExtraDetailAllowed implements \modules\membership_plan\IListingPackageExtraDetail
{
	public function getId()
	{
		return 'feature_slideshow_allowed';
	}

	public function getCaption()
	{
		return 'Slide Show Feature';
	}

	public function getType()
	{
		return 'boolean';
	}

	public function getExtraInfo()
	{
		return array
		(
			'value' => true,
			'input_template' => 'field_types^input/boolean_on_off.tpl',
		);
	}

	public static function getOrder()
	{
		return 300;
	}
}

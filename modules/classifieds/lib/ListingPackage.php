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

class ListingPackage extends \modules\membership_plan\lib\Package\PackageDetails
{
	public static function getSystemDetailsInfo()
	{
		static $systemDetails = array
								(
									array
									(
										'id'			=> 'listing_lifetime',
										'caption'		=> 'Listing Lifetime (days)',
										'type'			=> 'integer',
										'is_required'	=> false,
									),
									array
									(
										'id'			=> 'pic_limit',
										'caption'		=> 'Number of Pictures Allowed',
										'type'			=> 'integer',
										'is_required'	=> false,
									),
									array
									(
										'id'			=> 'video_allowed',
										'caption'		=> 'Is Video Allowed',
										'type'			=> 'boolean',
										'is_required'	=> false,
										'input_template'=> 'field_types^input/boolean_on_off.tpl',
									),
								);

		$extendedSystemDetails = $systemDetails;

		$extraDetails = new \core\ExtensionPoint('modules\membership_plan\IListingPackageExtraDetail');
		foreach ($extraDetails as $extraDetail)
		{
			$detail = array(
				'id' => 		$extraDetail->getId(),
				'caption' => 	$extraDetail->getCaption(),
				'type' => 		$extraDetail->getType(),
			);
			$detail = array_merge($detail, $extraDetail->getExtraInfo());
			$extendedSystemDetails[] = $detail;
		}

		return array_merge(parent::getSystemDetailsInfo(), $extendedSystemDetails);
	}
}

<?php
/**
 *
 *    Module: listing_repost v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_repost-7.5.0-1
 *    Tag: tags/7.5.0-1@19795, 2016-06-17 13:19:57
 *
 *    This file is part of the 'listing_repost' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_repost\apps\FrontEnd\scripts;

class DisplayProfileSettingsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display listing repost settings in user profile page';
	protected $moduleName = 'listing_repost';
	protected $functionName = 'display_profile_settings';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$userInfo = \App()->UserManager->getCurrentUserInfo();

		if (\App()->UserSocialNetworkAccessDataManager->getTwitterSetupStatus())
		{
			$templateProcessor->assign("twitterIsSetUp", true);
			$templateProcessor->assign("twitterStatus", \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser($userInfo['sid'], 'Twitter'));
		}

		$membershipPlanSIDsOfUserGroup = \App()->UserGroupManager->getMembershipPlanSIDsByUserGroupSID($userInfo['user_group_sid']);
		$templateProcessor->assign("userCanAddListings", !empty($membershipPlanSIDsOfUserGroup));

		$templateProcessor->display("profile_settings.tpl");
	}
}

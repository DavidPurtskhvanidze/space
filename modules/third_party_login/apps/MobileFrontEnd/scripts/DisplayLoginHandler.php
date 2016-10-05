<?php
/**
 *
 *    Module: third_party_login v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: third_party_login-7.3.0-1
 *    Tag: tags/7.3.0-1@18640, 2015-08-24 13:43:11
 *
 *    This file is part of the 'third_party_login' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\third_party_login\apps\MobileFrontEnd\scripts;

class DisplayLoginHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Youtube';
	protected $moduleName = 'third_party_login';
	protected $functionName = 'display_form';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$thirdPartyAuthUserGroupSid = \App()->SettingsFromDB->getSettingByName('third_party_auth_user_group_sid');
		$templateProcessor->assign("thirdPartyLoginIsSetUp", !empty($thirdPartyAuthUserGroupSid));

		$templateProcessor->assign("googleIsSetUp", \App()->ThirdPartyAuthManager->getGoogleSetupStatus());
		$templateProcessor->assign("facebookIsSetUp", \App()->ThirdPartyAuthManager->getFacebookSetupStatus());
		$templateProcessor->assign("twitterIsSetUp", \App()->ThirdPartyAuthManager->getTwitterSetupStatus());
		$templateProcessor->assign("HTTP_REFERER", \App()->Request['httpReferer']);
		$templateProcessor->assign("QUERY_STRING", \App()->Request['queryString']);
		$templateProcessor->display("third_party_login.tpl");
	}
}

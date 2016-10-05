<?php
/**
 *
 *    Module: ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19789, 2016-06-17 13:19:41
 *
 *    This file is part of the 'ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\ip_blocklist;

class IPBlocklistPlugin implements \core\IAfterInitFunction
{
	public function execute()
	{
		if (\App()->IpRangeManager->isIpInBlockList(\modules\ip_blocklist\lib\IpProcessor::getClientIpAsString()))
		{
			$message = str_replace
			(
				array('{site_url}', '{adminNotificationEmail}'),
				array(\App()->SystemSettings['SiteUrl'], \App()->SettingsFromDB->getSettingByName('notification_email')),
				\App()->SystemSettings['BlockedIPMessage']
			);
			throw new \lib\Http\ForbiddenException($message);
		}
	}
}

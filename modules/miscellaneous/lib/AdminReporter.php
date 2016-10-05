<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class AdminReporter
{
	var $expiredUserListingsLoggers = array();
	var $usersContractExpired = array();

	function addExpiredUserListingsLogger(&$logger)
	{
		if (\App()->SettingsFromDB->getSettingByName('notify_on_listing_expiration'))
		{
			$this->expiredUserListingsLoggers[] = $logger;
		}
	}
	function addUserContractExpired(&$user)
	{
		if (\App()->SettingsFromDB->getSettingByName('notify_on_user_contract_expiration'))
		{
			$this->usersContractExpired[] = $user;
		}
	}
	
	function report()
	{
		if (!empty($this->usersContractExpired) || !empty($this->expiredUserListingsLoggers))
		{
			$expiredContractsLog = array();
			foreach (array_keys($this->usersContractExpired) as $i)
			{
				$user = $this->usersContractExpired[$i];
				$expiredContractsLog[] = array
				(
					'user' => \App()->UserManager->createTemplateStructureForUser($user),
				);
			}

			$expiredListingsLog = array();
			foreach (array_keys($this->expiredUserListingsLoggers) as $i)
			{
				$logger = $this->expiredUserListingsLoggers[$i];
				$expiredListingsLog[] = array
				(
					'username' => $logger->getUsername(),
					'expiredListingsSid' => $logger->getExpiredListingsSid(),
				);
			}
			$parameters = array
			(
				'expiredContractsLog' => $expiredContractsLog,
				'expiredListingsLog' => $expiredListingsLog,
			);
			return \App()->EmailService->sendToAdmin('email_template:admin_expired_contracts_and_listings_report', $parameters);
		}
		return false;
	}
}

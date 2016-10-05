<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan\lib; 

class NotifySubscriptionExpirationApproachingTask extends \modules\miscellaneous\lib\ScheduledTaskBase
{
	public static function getOrder()
	{
		return 5000;
	}
	
	public function run()
	{
		$this->scheduler->log('Notifying users on subscription expiration date approaching');

		$expiresWithinDays = \App()->SettingsFromDB->getSettingByName('listing_and_subscription_notification_threshold');
		$fromDate = new \DateTime($this->scheduler->getLastRunTime());
		$fromDate->add(new \DateInterval('P'. ($expiresWithinDays + 1) . 'D'));
		$toDate = new \DateTime($this->scheduler->getStartTime());
		$toDate->add(new \DateInterval('P'. $expiresWithinDays . 'D'));
		
		$expiredContracts = \App()->ContractManager->getSIDsOfContractsExpiredBeetwen($fromDate->format('Y-m-d H:i:s'), $toDate->format('Y-m-d H:i:s'));
		$this->scheduler->log(sprintf('Found %d contracts which will expire soon.', count($expiredContracts)));
		foreach ($expiredContracts as $contractId)
		{
			$user = \App()->UserManager->getObjectBySID(\App()->UserManager->getUserSIDByContractID($contractId));
			if (is_null($user)) continue;
			$user = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($user);

			$this->scheduler->log(sprintf('Sending email to %s (%s)', (string) $user['username'], (string) $user['email']));
			$this->sendNotificationLetter($user, $expiresWithinDays);
		}
	}
	
	private function sendNotificationLetter($user, $expiresWithinDays)
	{
		return \App()->EmailService->send(
			(string) $user['email'],
			'email_template:subscription_expiration_approaching_notification',
			array(
				'user' => $user,
				'numberOfDays' => $expiresWithinDays
			)
		);
	}
}

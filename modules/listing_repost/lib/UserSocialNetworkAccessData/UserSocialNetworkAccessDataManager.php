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


namespace modules\listing_repost\lib\UserSocialNetworkAccessData;

class UserSocialNetworkAccessDataManager extends \lib\ORM\ObjectManager implements \core\IService
{
	public function init()
	{
		$this->dbManager = new \lib\ORM\ObjectDBManager();
	}

	public function createUserSocialNetworkAccessData($data)
	{
		$object = new UserSocialNetworkAccessData();
		$object->setDetails($this->createUserSocialNetworkAccessDataDetails());
		$object->incorporateData($data);
		if (isset($data['sid'])) $object->setSid($data['sid']);
		return $object;
	}

	private function createUserSocialNetworkAccessDataDetails()
	{
		$details = new UserSocialNetworkAccessDataDetails();
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildProperties();
		return $details;
	}

	public function getAccessDataForUser($userSid, $providerId)
	{
		$data = \App()->DB->query("SELECT * FROM `listing_repost_social_network_service_data` WHERE `user_sid` = ?s AND `provider_id` = ?s", $userSid, $providerId);
		return !empty($data) ?  $this->createUserSocialNetworkAccessData(array_pop($data))->getPropertyValue('access_token') : null;
	}

    public function deleteAccessDataForUser($userSid, $providerId)
    {
        \App()->DB->query("DELETE FROM `listing_repost_social_network_service_data` WHERE `user_sid` = ?s AND `provider_id` = ?s", $userSid, $providerId);
    }

	public function disableListingRepostStatusForUser($userSid, $providerId)
	{
		\App()->DB->query("UPDATE `listing_repost_social_network_service_data` SET `enabled` = FALSE WHERE `user_sid` = ?s AND `provider_id` = ?s", $userSid, $providerId);
	}

	public function enableListingRepostStatusForUser($userSid, $providerId)
	{
		\App()->DB->query("UPDATE `listing_repost_social_network_service_data` SET `enabled` = TRUE WHERE `user_sid` = ?s AND `provider_id` = ?s", $userSid, $providerId);
	}

	public function getListingRepostStatusForUser($userSid, $providerId)
	{
		$data = \App()->DB->query("SELECT * FROM `listing_repost_social_network_service_data` WHERE `user_sid` = ?s AND `provider_id` = ?s", $userSid, $providerId);
		return !empty($data) ?  $this->createUserSocialNetworkAccessData(array_pop($data))->getPropertyValue('enabled') : null;
	}
	public function getFacebookSetupStatus()
	{
		return \App()->SettingsFromDB->getSettingByName('facebook_app_id') != "" && \App()->SettingsFromDB->getSettingByName('facebook_app_secret') != "";
	}
	public function getTwitterSetupStatus()
	{
		return
				\App()->SettingsFromDB->getSettingByName('twitter_consumer_key') != ""
				&& \App()->SettingsFromDB->getSettingByName('twitter_consumer_secret') != ""
				&& \App()->SettingsFromDB->getSettingByName('twitter_access_token') != ""
				&& \App()->SettingsFromDB->getSettingByName('twitter_access_token_secret') != "";
	}
}

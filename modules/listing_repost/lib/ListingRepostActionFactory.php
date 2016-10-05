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


namespace modules\listing_repost\lib;

class ListingRepostActionFactory
{
	public function createPostListingMessageToFacebookAction($listing)
	{
		$userSid = $listing->getUserSID();
		return $this->createPostMessageToProviderAction($userSid, array('listing' => \App()->ObjectMother->createListingDisplayer()->wrapListing($listing)), 'Facebook', 'classifieds^repost_message_templates/facebook_message.tpl');
	}

	public function createPostListingMessageToTwitterAction($listing)
	{
		$userSid = $listing->getUserSID();
		return $this->createPostMessageToProviderAction($userSid, array('listing' => \App()->ObjectMother->createListingDisplayer()->wrapListing($listing)), 'Twitter', 'classifieds^repost_message_templates/twitter_message.tpl');
	}

	public function createPostListingMessageToAdminFacebookAction($listing)
	{
		return $this->createPostMessageToProviderAction(0, array('listing' => \App()->ObjectMother->createListingDisplayer()->wrapListing($listing)), 'Facebook', 'classifieds^repost_message_templates/facebook_message.tpl');
	}

	public function createPostListingMessageToAdminTwitterAction($listing)
	{
		return $this->createPostMessageToProviderAction(0, array('listing' => \App()->ObjectMother->createListingDisplayer()->wrapListing($listing)), 'Twitter', 'classifieds^repost_message_templates/twitter_message.tpl');
	}

	public function createPostPublicationMessageToFacebookAction($article)
	{
		return $this->createPostMessageToProviderAction(0, array('article' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($article)), 'Facebook', 'publications^repost_message_templates/facebook_message.tpl');
	}

	public function createPostPublicationMessageToTwitterAction($article)
	{
		return $this->createPostMessageToProviderAction(0, array('article' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($article)), 'Twitter', 'publications^repost_message_templates/twitter_message.tpl');
	}

	public function createPostMessageToProviderAction($userSid, $objectsToAssign, $providerId, $template)
	{
		$factory = new \modules\third_party_auth_providers\lib\ThirdPartyAuthProviderFactory();
		$templateProcessor = \App()->getTemplateProcessor();
		foreach ($objectsToAssign as $objectName => $object)
			$templateProcessor->assign($objectName, $object);
		$templateProcessor->assign("frontEndUrl", \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl'));

		$instance = new PostMessageToProviderAction();
		$instance->setProvider($factory->getProvider($providerId));
		$instance->setAccessData(\App()->UserSocialNetworkAccessDataManager->getAccessDataForUser($userSid, $providerId));
		$instance->setMessage(strip_tags($templateProcessor->fetch($template)));
		return $instance;
	}
}

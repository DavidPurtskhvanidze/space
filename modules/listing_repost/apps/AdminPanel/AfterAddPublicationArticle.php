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


namespace modules\listing_repost\apps\AdminPanel;
 
class AfterAddPublicationArticle implements \modules\publications\apps\AdminPanel\IAfterAddPublicationArticle
{
	private $article;
	
	public function setArticle($article)
	{
		$this->article = $article;
	}
	
	public function perform()
	{
		$listingRepostActionFactory = new \modules\listing_repost\lib\ListingRepostActionFactory();
		
		if (!\App()->Request['doNotRepostToFacebook'] && \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, "Facebook"))
		{
			$listingRepostActionFactory->createPostPublicationMessageToFacebookAction($this->article)->perform();
		}
		if (!\App()->Request['doNotRepostToTwitter'] && \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, "Twitter"))
		{
			$listingRepostActionFactory->createPostPublicationMessageToTwitterAction($this->article)->perform();
		}
	}
}

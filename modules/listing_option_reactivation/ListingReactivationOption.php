<?php
/**
 *
 *    Module: listing_option_reactivation v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_option_reactivation-7.5.0-1
 *    Tag: tags/7.5.0-1@19794, 2016-06-17 13:19:54
 *
 *    This file is part of the 'listing_option_reactivation' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_option_reactivation;

class ListingReactivationOption implements \modules\classifieds\IAdditionalListingOption
{
	/**
	 * Listing Object
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;
	
	public function setListing($listing)
	{
		$this->listing = $listing;
	}
	
	public function isAvailable()
	{
		if ($this->listing->isActive())
		{
			$reactivation = \App()->ListingReactivationManager->getListingReactivationByListingSid($this->listing->getSID());
			return ($reactivation) ? !$reactivation->isActive() : true;
		}
		return false;
	}
	
	public function getId()
	{
		return 'listing_option_reactivation';
	}
	
	public function getCaption()
	{
		return 'Listing Reactivation';
	}

	public function getDescription()
	{
		$url = \App()->SystemSettings['SiteUrl'];
		$urlParams = array(
			'return_uri' => $url . \App()->PageManager->getPageUri() . '?' . http_build_query($_REQUEST),
			'listing_sid' => $this->listing->getSID()
		);

        $templateProcessor = \App()->getTemplateProcessor();
		if (!\App()->ListingReactivationManager->isListingReactivationExist($this->listing->getSID()))
		{
			$url .= \App()->PageRoute->getSystemPageURI('listing_option_reactivation', 'select_listing_package') . '?' . http_build_query($urlParams);
            $templateProcessor->assign('url', $url);
            return $templateProcessor->fetch("listing_option_reactivation^select_listing_package_message.tpl");
		}
		else
		{
            $url .= \App()->PageRoute->getSystemPageURI('listing_option_reactivation', 'manage_reactivation_options') . '?' . http_build_query($urlParams);
            $templateProcessor->assign('url',$url);
            return $templateProcessor->fetch("listing_option_reactivation^manage_reactivation_options_message.tpl");
		}
	}

	public function getAdditionalScript()
	{
		if (!\App()->ListingReactivationManager->isListingReactivationExist($this->listing->getSID()))
		{
return <<<HereDocScript
	<script type="text/javascript">
		$(document).ready(function() {
			$('#{$this->getId()}').attr('checked', false);
			$('#{$this->getId()}').attr('disabled', 'disabled');
		});
	</script>
HereDocScript;
		}
	}

	public function getPrice()
	{
		$reactivation = \App()->ListingReactivationManager->getListingReactivationByListingSid($this->listing->getSID());
		return ($reactivation) ? $reactivation->getTotalPrice() : 0;
	}

	public function activateOption()
	{
		\App()->ListingReactivationManager->activateListingReactivationByListingSid($this->listing->getSID());
	}
}

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


namespace modules\listing_option_reactivation\apps\FrontEnd\scripts;

class ManageReactivationOptionsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Manage Listing Reactivation Options';
	protected $moduleName = 'listing_option_reactivation';
	protected $functionName = 'manage_reactivation_options';
	protected $rawOutput = true;
	/**
	 * Listing
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;
	/**
	 * Predefined Request Data
	 * @var array
	 */
	private $predefinedRequestData;
	/**
	 * Free features
	 * @var array
	 */
	private $freeFeatures;
	/**
	 * Paid features
	 * @var array
	 */
	private $paidFeatures;
	/**
	 * Reactivation
	 * @var \modules\listing_option_reactivation\lib\ListingReactivation
	 */
	private $reactivation;
	
	public function respond()
	{
		$this->initEnvironment();

		if (\App()->Request['action'] == 'save_options' && false !== ($formData = $this->extractFormData()))
		{
			foreach ($formData as $propertyName => $propertyValue)
			{
				$this->reactivation->setPropertyValue($propertyName, $propertyValue);
			}
			\App()->ListingReactivationManager->saveObject($this->reactivation);
			throw new \lib\Http\RedirectException(\App()->Request['return_uri']);
		}
		else {
			$selectedFeatures = $this->reactivation->getOptionsToActivate();
			if (empty($selectedFeatures))
			{
				$allFeatures = array_merge(array_keys($this->freeFeatures), array_keys($this->paidFeatures));
				foreach($allFeatures as $featureId)
				{
					if ((bool) $this->listing->getPropertyValue($featureId))
					{
						$selectedFeatures[] = $featureId;
					}
				}
			}

			$templateProcessor = \App()->getTemplateProcessor();
			$templateProcessor->assign('activationPrice', $this->reactivation->getActivationPrice());
			$templateProcessor->assign('selectedFeatures', $selectedFeatures);
			$templateProcessor->assign('availableFreeFeatures', $this->freeFeatures);
			$templateProcessor->assign('availablePaidFeatures', $this->paidFeatures);
			$templateProcessor->assign('predefinedRequestData', $this->predefinedRequestData);
			$templateProcessor->assign('numberOfDigitsAfterDecimal', \App()->PaymentSystemManager->getCurrentPaymentMethod()->getNumberOfDigitsAfterDecimal());
			$templateProcessor->display('select_listing_options.tpl');
		}
	}
	
	private function initEnvironment()
	{
		$this->listing = \App()->ListingManager->getObjectBySID(\App()->Request['listing_sid']);
		if (is_null($this->listing))
		{
			throw new \lib\Http\NotFoundException('Listing with the specified sid was not found');
		}
		elseif ($this->listing->getUserSID() != \App()->UserManager->getCurrentUserSID())
		{
			throw new \lib\Http\ForbiddenException('You are not the owner of the listing');
		}
		elseif (!\App()->ListingReactivationManager->isListingReactivationExist(\App()->Request['listing_sid']))
		{
			throw new \lib\Http\NotFoundException('Listing reactivation was not found');
		}
		
		$this->reactivation = \App()->ListingReactivationManager->getListingReactivationByListingSid(\App()->Request['listing_sid']);
		
		$this->freeFeatures = \App()->ListingFeaturesManager->getFreeFeaturesByPackageInfo($this->reactivation->getPackageInfo());
		$this->paidFeatures = \App()->ListingFeaturesManager->getPaidFeaturesByPackageInfo($this->reactivation->getPackageInfo());
		
		$this->predefinedRequestData['listing_sid'] = \App()->Request['listing_sid'];
		$this->predefinedRequestData['return_uri'] = \App()->Request['return_uri'];
	}
	
	private function extractFormData()
	{
		$selectedOptionIds = \App()->Request['selectedOptionIds'];
		if (!is_array($selectedOptionIds)) 
		{
			$selectedOptionIds = array();
		}
		$features = $this->freeFeatures + $this->paidFeatures;
		foreach ($selectedOptionIds as $key => $value)
		{
			if (!isset($features[$value]))
			{
				unset($selectedOptionIds[$key]);
			}
		}
		
		$selectedOptionIds[] = 'activation';
		
		return array(
			'options_to_activate' => $selectedOptionIds
		);
	}
}

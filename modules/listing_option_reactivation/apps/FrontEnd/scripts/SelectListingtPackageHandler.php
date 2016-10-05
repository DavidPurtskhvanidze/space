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

class SelectListingtPackageHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Select Package For Listing Reactivation';
	protected $moduleName = 'listing_option_reactivation';
	protected $functionName = 'select_listing_package';
	protected $rawOutput = true;

	public function respond()
	{
		$this->initEnvironment();
		
		$currentUser = \App()->UserManager->getCurrentUser();
		$templateProcessor = \App()->getTemplateProcessor();
		
		if (!$currentUser->hasContract())
		{
			$templateProcessor->assign('contract_expired', true);
		}
		else
		{
			if (\App()->Request['action'] == 'package_selected' && false !== ($formData = $this->extractFormData()))
			{
				$reactivation = \App()->ListingReactivationManager->createListingReactivation($formData);
				\App()->ListingReactivationManager->saveObject($reactivation);
				throw new \lib\Http\RedirectException(\App()->Request['return_uri']);
			}
			else {
				$contract = \App()->ContractManager->getContractBySID($currentUser->getContractID());
				$packageInfo = $contract->getListingPackagesInfo();
				$templateProcessor->assign('listing_sid', \App()->Request['listing_sid']);
				$templateProcessor->assign('return_uri', \App()->Request['return_uri']);
				$templateProcessor->assign('listing_packages', $packageInfo);
			}
		}
		$templateProcessor->display('listing_package_choice.tpl');
	}
	
	private function initEnvironment()
	{
		$listing = \App()->ListingManager->getObjectBySID(\App()->Request['listing_sid']);
		if (is_null($listing))
		{
			throw new \lib\Http\NotFoundException('Listing with the specified sid was not found');
		}
		elseif ($listing->getUserSID() != \App()->UserManager->getCurrentUserSID())
		{
			throw new \lib\Http\ForbiddenException('You are not the owner of the listing');
		}
		elseif (\App()->ListingReactivationManager->isListingReactivationExist(\App()->Request['listing_sid']))
		{
			throw new \lib\Http\RedirectException(\App()->Request['return_uri']);
		}
	}
	
	private function extractFormData()
	{
		if (empty(\App()->Request['listing_package_sid']))
		{
			\App()->ErrorMessages->addMessage('LISTING_REACTIVATION_PACKAGE_NOT_SELECTED');
			return false;
		}

		$contract = \App()->ContractManager->getContractBySID(\App()->UserManager->getCurrentUser()->getContractID());
		
		$formData = array(
			'user_sid' => \App()->UserManager->getCurrentUserSID(),
			'listing_sid' => \App()->Request['listing_sid'],
			'package_sid' => \App()->Request['listing_package_sid'],
			'package_info' => $contract->getPackageInfoByPackageSID(\App()->Request['listing_package_sid']),
			'options_to_activate' => array(),
		);

		if (!$formData['package_info'])
		{
			\App()->ErrorMessages->addMessage('INVALID_VALUE_PACKAGE_SID');
			return false;
		}
		
		return $formData;
	}
}


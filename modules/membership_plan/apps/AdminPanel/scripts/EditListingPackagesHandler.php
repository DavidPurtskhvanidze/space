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


namespace modules\membership_plan\apps\AdminPanel\scripts;

class EditListingPackagesHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'membership_plan';
	protected $functionName = 'edit_lisitng_packages';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$requestReflector = \App()->ObjectMother->createRequestReflector();
		
	    $listingsSIDs = $requestReflector->get('listing_sids');
		if(empty($listingsSIDs))
		{
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('manage_listings') . '?action=restore&searchId=' . $requestReflector->get('searchId'));
			return;
		}

		$package = \App()->PackageManager->createPackage(null, '\modules\classifieds\lib\ListingPackage', 0, $_REQUEST);
		
		$form = \App()->PackageManager->getEditListingPackageForm($package);
		$form->registerTags($templateProcessor);

		if (($requestReflector->get('action') == 'save_listing_packages') && $form->isDataValid())
		{
			$newListingPackageInfo = $this->filterPackageInfoFromEmptyValues($package->getHashedFields());
			foreach($listingsSIDs as $listingsSID)
			{
	            $listingPackageInfo = \App()->ListingPackageManager->getPackageInfoByListingSID($listingsSID);
	            \App()->ListingPackageManager->updatePackage($listingsSID, array_merge($listingPackageInfo, $newListingPackageInfo));
			}
			
			$templateProcessor->assign('actionCompleted', true);
		}
		else
		{
			$templateProcessor->assign('listingSIDs', $requestReflector->get('listing_sids'));
			$templateProcessor->assign('formFields', $form->getFormFieldsInfo());
		}

		$templateProcessor->assign('searchId', $requestReflector->get('searchId'));
		$templateProcessor->display('edit_listing_packages.tpl');
	}
	private function filterPackageInfoFromEmptyValues($packageInfo)
	{
		foreach($packageInfo as $fieldName => $fieldValue)
		{
			if ($fieldValue === '')
			{
				unset($packageInfo[$fieldName]);
			}
		}

		return $packageInfo;
	}
}

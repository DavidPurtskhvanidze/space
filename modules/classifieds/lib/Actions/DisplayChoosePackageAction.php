<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib\Actions;

use lib\Http\RedirectException;

class DisplayChoosePackageAction
{
	/**
	 * @var \modules\membership_plan\lib\Contract\Contract
	 */
	private $contract;

	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;

	private $predefinedRequestData = array();

	public function perform()
	{
		$listingPackagesInfo = $this->contract->getListingPackagesInfo();
		$listingMeetPackageConditionsValidator = \App()->ObjectMother->createListingMeetPackageConditionsValidator($this->listing, $this->contract);
		$listingPackagesInfo = array_filter($listingPackagesInfo, function($packageInfo) use ($listingMeetPackageConditionsValidator)
		{
			/**
			 * @var \lib\Validation\ListingMeetPackageConditionsValidator $listingMeetPackageConditionsValidator
			 */
			return $listingMeetPackageConditionsValidator->isValid($packageInfo['sid']);
		});

        $templateProcessor = \App()->getTemplateProcessor();

        if (empty($listingPackagesInfo)){ //значить не прошло валидацию пакета
            $errors = $listingMeetPackageConditionsValidator->getErrors();
            $errorsData = $listingMeetPackageConditionsValidator->getErrorsData();
            foreach($errors as $error) {
                \App()->ErrorMessages->addMessage($error, isset($errorsData[$error]) ? $errorsData[$error] : []);
            }
            $templateProcessor->assign('listing', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->listing));
            $templateProcessor->display("classifieds^invalid_listing_data_for_package.tpl");

        } else {
            $templateProcessor->assign("listingSid", null);
            $templateProcessor->assign("listing_packages", $listingPackagesInfo);
            $templateProcessor->assign("predefinedRequestData", $this->predefinedRequestData);
            $templateProcessor->assign("METADATA", array
            (
                'listing_package' => array
                (
                    'name' => array('domain' => 'Miscellaneous'),
                    'description' => array('domain' => 'Miscellaneous'),
                )
            ));
            $templateProcessor->display("classifieds^choose_package.tpl");
        }
	}

	public function setContract($contract)
	{
		$this->contract = $contract;
	}

	public function setPredefinedRequestData($predefinedRequestData)
	{
		$this->predefinedRequestData = $predefinedRequestData;
	}

	/**
	 * @param \modules\classifieds\lib\Listing\Listing $listing
	 */
	public function setListing($listing)
	{
		$this->listing = $listing;
	}
}

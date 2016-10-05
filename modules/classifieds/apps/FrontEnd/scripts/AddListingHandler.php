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


namespace modules\classifieds\apps\FrontEnd\scripts;

class AddListingHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Add Listing';
	protected $moduleName = 'classifieds';
	protected $functionName = 'add_listing';

	/**
	 * @var AddListingHandlerHelper
	 */
	private $helper;

	public function respond()
	{
		$helper = new AddListingHandlerHelper();
		$helper->setTemplateProcessor(\App()->getTemplateProcessor());
		$helper->setCurrentUser(\App()->UserManager->getCurrentUser());
		$helper->setLogger(new ManageListingOptionsHandlerLogger());

		$this->setHelper($helper);
		$this->perform();
	}

	public function perform()
	{
		if (!$this->helper->canCurrentUserAddListing())
		{
			$this->helper->displayAddListingError();
		}
		elseif (!$this->helper->isListingPackageChosen())
		{
			$this->helper->displayChoosePackage();
		}
		elseif (!$this->helper->isCategoryChosen())
		{
			$this->helper->displayChooseCategory();
		}
		else
		{
			$this->helper->defineListingReservedSid();
			$this->helper->fillFormIfSetListingTpl();
			$this->helper->defineAddListingForm();
			$this->helper->defineCurrentStep();

			if ($this->helper->isLastStepFormSubmitted() && $this->helper->isListingDataValid())
			{
				$this->helper->saveListing();
				$this->performListingAndOptionsActivation();
				$this->helper->displayOptionsAppliedMessage();
			}
			else
			{
				$this->helper->displayAddListingForm();
			}
		}
	}

	public function performListingAndOptionsActivation()
	{
		if ($this->helper->isListingActivationFree())
		{
			$this->helper->activateListingByUser();
			if ($this->helper->isListingActive())
			{
				$this->helper->activateFreeOptions();
			}
			else
			{
				$this->helper->addFreeOptionsToContainer();
			}
		}
		else
		{
			$this->helper->addActivationToBasket();
			$this->helper->addFreeOptionsToContainer();
		}
		$this->helper->addPaidOptionsToBasket();
	}

	public function setHelper($helper)
	{
		$this->helper = $helper;
	}
}

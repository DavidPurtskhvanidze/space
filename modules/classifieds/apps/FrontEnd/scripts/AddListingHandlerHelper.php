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

class AddListingHandlerHelper
{
	/**
	 * @var ManageListingOptionsHandlerLogger
	 */
	private $logger;
	private $listingReservedSid;
	/**
	 * @var \lib\Forms\Form
	 */
	private $addListingFormObject;

	private $categorySid;
	/**
	 * @var \modules\smarty_based_template_processor\lib\TemplateProcessor
	 */
	private $templateProcessor;

	/**
	 * @var \modules\users\lib\User\User
	 */
	private $currentUser;
	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;
	private $error;
	private $category_id;
	private $listingPackageSID;

	/**
	 * @var \core\SessionContainer
	 */
	private $sessionContainer;

	public function __construct()
	{
		if (\App()->Request['restoreLastState'])
		{
			$lastState = \App()->Session->getValue('addListingLastState');
			\App()->Request['action_go_to_step'] = 1;
			\App()->Request['step'] = $lastState['step'];
			\App()->Request['add_listing_session_id'] = $lastState['add_listing_session_id'];
		}
		$addListingSessionId = \App()->Request->getValueOrDefault('add_listing_session_id', uniqid());
		$this->sessionContainer = \App()->Session->getContainer($addListingSessionId);
		$this->defineGlobalTemplateVariable();
	}
	
	private function defineGlobalTemplateVariable()
	{
		if (\App()->Request['action_go_to_step']) return;
		if (strpos($_SERVER['REQUEST_URI'],'restoreLastState') !== false) return;
		$delimiter = (strpos($_SERVER['REQUEST_URI'],'?') !== false) ? '&amp;' : '&#63;';
		\App()->GlobalTemplateVariable->setGlobalTemplateVariable('return_back_extra_params',"{$delimiter}restoreLastState=1");
	}

	public function isListingActivationFree()
	{
		$package_info = $this->listing->getListingPackageInfo();
		return $package_info['price'] == 0;
	}

	public function isListingActive()
	{
		return $this->listing->isActive();
	}

	public function activateListingByUser()
	{
		$activateListingByUserAction = \App()->ClassifiedsActionsFactory->createActivateListingByUserAction($this->listing);
		$activateListingByUserAction->perform();
		$this->logger->logListingActivated();
	}

	public function activateFreeOptions()
	{
		$action = \App()->ClassifiedsActionsFactory->createActivateOptionsAction($this->listing, $this->getSelectedFreeOptionIds());
		$action->perform();
		$this->logger->logOptionsActivated($this->getSelectedFreeOptionIds());
	}

	public function addPaidOptionsToBasket()
	{
		$addOptionsToBasketAction = \App()->BasketActionsFactory->createAddOptionsToBasketAction($this->listing->getSID(), $this->listing->getUserSID(), $this->getSelectedPaidOptionIds());
		$addOptionsToBasketAction->perform();
		$this->logger->logOptionsAddedToBasket($this->getSelectedPaidOptionIds());
	}

	public function addFreeOptionsToContainer()
	{
		$addOptionsToContainerAction = \App()->BasketActionsFactory->createAddOptionsToContainerAction($this->listing->getSID(), $this->getSelectedFreeOptionIds());
		$addOptionsToContainerAction->perform();
		$this->logger->logOptionsAddedToContainer($this->getSelectedFreeOptionIds());
	}

	public function addActivationToBasket()
	{
		$addActivationToBasketAction = \App()->BasketActionsFactory->createAddActivationToBasketAction($this->listing->getSID(), $this->listing->getUserSID());
		$addActivationToBasketAction->perform();
		$this->logger->logListingActivationAddedToBasket();
	}

	private function getSelectedFreeOptionIds()
	{
		$freeFeaturesIds = array_merge(
			\App()->ListingFeaturesManager->getFreeFeatureIdsByPackageInfo($this->listing->getListingPackageInfo()),
			\App()->AdditionalListingOptionManager->getFreeOptionIdsByListing($this->listing)
		);
		return array_intersect($freeFeaturesIds, $this->getSelectedOptionIds());
	}

	private function getSelectedPaidOptionIds()
	{
		$paidFeaturesIds = array_merge(
			\App()->ListingFeaturesManager->getPaidFeatureIdsByPackageInfo($this->listing->getListingPackageInfo()),
			\App()->AdditionalListingOptionManager->getPaidOptionIdsByListing($this->listing)
		);

		return array_intersect($paidFeaturesIds, $this->getSelectedOptionIds());
	}

	private function getSelectedOptionIds()
	{
		$selectedOptionIds = \App()->Request['selectedOptionIds'];
		return is_array($selectedOptionIds) ? $selectedOptionIds : array();
	}

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	// -------------- moved from AddListingHandler ---------------------

	public function canCurrentUserAddListing()
	{
		if (is_null($this->currentUser))
		{
			$this->error = 'NOT_LOGGED_IN';
			return false;
		}
		if (!$this->currentUser->hasContract())
		{
			$this->error = 'NO_CONTRACT';
			return false;
		}
		$contract = \App()->ContractManager->getContractBySID($this->currentUser->getContractID());
		if ($contract->isExpired())
		{
			$this->error = 'CONTRACT_EXPIRED';
			return false;
		}
		if (!count($contract->getListingPackagesInfo()) > 0)
		{
			$this->error = 'NO_LISTING_PACKAGE_AVAILABLE';
			return false;
		}
		$availableListingsAmount = $contract->getAvailableListingsAmount();
		if ($availableListingsAmount <= \App()->ListingManager->getListingsNumberByUserSID($this->currentUser->getSID()) && $availableListingsAmount !== '')
		{
			$this->error = 'LISTINGS_NUMBER_LIMIT_EXCEEDED';
			return false;
		}
		return true;
	}

	public function displayAddListingError()
	{
		if ($this->error == 'NO_CONTRACT' || $this->error == 'CONTRACT_EXPIRED')
		{
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('user_subscription') . '?returnBackUri=' . \App()->PageRoute->getPageURIById('listing_add'));
		}
		$this->templateProcessor->assign("error", $this->error);
		$this->templateProcessor->display("add_listing_error.tpl");
	}

	public function isListingPackageChosen()
	{
		$this->listingPackageSID = $this->sessionContainer->getValue('listing_package_sid');
		$contract = \App()->ContractManager->getContractBySID($this->currentUser->getContractID());
		$listing_packages_info = $contract->getListingPackagesInfo();
		if (!empty(\App()->Request['listing_package_sid']) && $contract->isListingPackageAvailableBySID(\App()->Request['listing_package_sid']))
		{
			$this->listingPackageSID = $_REQUEST['listing_package_sid'];
		}
		elseif (count($listing_packages_info) == 1)
		{
			$listing_package_info = $listing_packages_info[0];
			$this->listingPackageSID = $listing_package_info['sid'];
		}
		$this->sessionContainer->setValue('listing_package_sid', $this->listingPackageSID);
		$this->templateProcessor->assign("listing_package_sid", $this->listingPackageSID);
		return !is_null($this->listingPackageSID);
	}

	public function displayChoosePackage()
	{
		$contract = \App()->ContractManager->getContractBySID($this->currentUser->getContractID());
		$listing_packages_info = $contract->getListingPackagesInfo();

		$this->templateProcessor->assign("listing_packages", $listing_packages_info);
		$this->templateProcessor->display("listing_package_choice.tpl");
	}

	public function isCategoryChosen()
	{
		$this->category_id = $this->sessionContainer->getValue('category_id');
		$categories_info = \App()->CategoryManager->getAllCategoriesInfo();
		if (!empty($_REQUEST['category_id']))
		{
			$this->category_id = $_REQUEST['category_id'];
		}
		elseif (count($categories_info) == 2)
		{
			$category_info = $this->getSingleCategory($categories_info);
			$this->category_id = $category_info['id'];
		}
		$this->categorySid = \App()->CategoryManager->getCategorySIDByID($this->category_id);
		$this->sessionContainer->setValue('category_id', $this->category_id);
		return !is_null($this->category_id);
	}

	public function displayChooseCategory()
	{
		/**
		 * @var \modules\classifieds\lib\Category\CategoryNode $node
		 */
		$node = \App()->CategoryTree->getNode(\App()->CategoryManager->getRootId());
		$this->templateProcessor->assign("category", $node->toArray());
		$this->templateProcessor->display("add_listing_choose_category.tpl");
	}

	public function defineAddListingForm()
	{
		$listingData = array_merge($this->getAlreadyDefinedListingData(), \App()->Request->getRequest());
		$listing = \App()->ObjectMother->getListingFactory()->getListing($listingData, $this->categorySid);
		$propertiesToExclude = array('sid', 'user_sid', 'activation_date', 'type', 'category_sid', 'listing_package', 'expiration_date', 'active', 'id', 'moderation_status', 'views', 'package', 'pictures', 'user', 'username', 'keywords', 'category', 'meta_keywords', 'meta_description', 'page_title');
		array_walk($propertiesToExclude, array($listing, 'deleteProperty'));
		\App()->ListingFeaturesManager->disableModifyingListingFeatures($listing);
		// need to exclude fields with type 'calendar' as they are not editable during the listing add
		$listing->deletePropertiesByTypes(array('calendar'));

		$listing->setSID($this->listingReservedSid);
		$listing->setUserSID($this->currentUser->getSID());

		$contract = \App()->ContractManager->getContractBySID($this->currentUser->getContractID());
		$listing_package_info = $contract->getPackageInfoByPackageSID($this->listingPackageSID);
		$listing->setListingPackageInfo($listing_package_info);

		$this->addListingFormObject = new \lib\Forms\Form($listing);
		$this->addListingFormObject->registerTags($this->templateProcessor);
	}

	public function defineListingReservedSid()
	{
		if (!is_null($this->sessionContainer->getValue('reservedSid')))
		{
			$this->listingReservedSid = $this->sessionContainer->getValue('reservedSid');
		}
		else
		{
			$this->listingReservedSid = \App()->ListingManager->reserveSidForListing();
			$this->sessionContainer->setValue('reservedSid', $this->listingReservedSid);
		}
	}

	public function isLastStepFormSubmitted()
	{
		return !is_null(\App()->Request['action_add']);
	}

	public function isListingDataValid()
	{
		return $this->addListingFormObject->isDataValid();
	}

	public function displayAddListingForm()
	{
		$contract = \App()->ContractManager->getContractBySID($this->currentUser->getContractID());
		$listing_package_info = $contract->getPackageInfoByPackageSID($this->listingPackageSID);

		$this->templateProcessor->registerPlugin('block', 'step', array($this, 'registerStep'));
		$this->templateProcessor->registerPlugin('function', 'addListingForm', array($this, 'addListingForm'));

		$this->templateProcessor->assign('freeFeatures', \App()->ListingFeaturesManager->getFreeFeaturesByPackageInfo($listing_package_info));
		$this->templateProcessor->assign('paidFeatures', \App()->ListingFeaturesManager->getPaidFeaturesByPackageInfo($listing_package_info));
		$this->templateProcessor->assign('selectedOptionIds', is_array(\App()->Request['selectedOptionIds']) ? \App()->Request['selectedOptionIds'] : array());
		$this->templateProcessor->assign("package", $listing_package_info);
		$this->templateProcessor->assign("form_fields", $this->addListingFormObject->getFormFieldsInfo());
		$this->templateProcessor->assign("calendarTypeFieldIds", $this->addListingFormObject->getFormFieldsIdsByType('calendar'));
		$this->templateProcessor->assign("ancestors", array_reverse(\App()->CategoryTree->getAncestorsInfo($this->categorySid)));
		$this->templateProcessor->assign('inputFormTemplate', \App()->CategoryManager->getCategoryInputTemplateFileName($this->categorySid));
		$this->templateProcessor->assign('maxReachedStepsCount', $this->getMaxReachedStepsCount());
		$this->templateProcessor->assign('add_listing_session_id', $this->sessionContainer->getId());
		$this->templateProcessor->assign('listing', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->addListingFormObject->getObject()));
		$this->templateProcessor->assign('listingAction', 'add');
		$this->templateProcessor->assign('categoryId', $this->category_id);
		$this->templateProcessor->display('add_listing.tpl');
	}

	public function fillFormIfSetListingTpl()
	{
		if(!empty(\App()->Request['fill_from_listing']))
		{
			$listingTmp = \App()->ListingManager->getListingBySid(\App()->Request['fill_from_listing']);

			$listingTmp->setSID($this->listingReservedSid);
			$propertiesToExclude = ['sid', 'user_sid', 'activation_date', 'type', 'category_sid', 'listing_package', 'expiration_date', 'active', 'id', 'moderation_status', 'views', 'package', 'pictures', 'user', 'username', 'keywords', 'category', 'meta_keywords', 'meta_description', 'page_title', 'Video'];
			array_walk($propertiesToExclude, [$listingTmp, 'deleteProperty']);
			\App()->ListingFeaturesManager->disableModifyingListingFeatures($listingTmp);
			// need to exclude fields with type 'calendar' as they are not editable during the listing add
			$listingTmp->deletePropertiesByTypes(['calendar']);

			$listingData = [];
			$propertyIds = array_keys($listingTmp->getProperties());
			foreach($propertyIds as &$id)
			{
				$property = $listingTmp->getProperty($id);
				if ($property->getType() == 'tree')
				{
					$listingData[$id] = $property->getPropertyVariablesToAssign()['value'];
				}
				else
				{
					$listingData[$id] = $property->getValue();
				}
			}
			\App()->ListingManager->updateListingPartially($listingTmp, $listingData);
			$_REQUEST['action_forward'] = 'next';
		}
	}

	public function defineCurrentStep()
	{
		if (\App()->Request['action_add'])
		{
			$currentStep = \App()->Request['step'];
		}
		elseif (\App()->Request['action_forward'])
		{
			$propertiesToCheck = array_merge(array_keys(\App()->Request->getRequest()), array_keys($_FILES));
			if ($this->addListingFormObject->isDataValidPartially($propertiesToCheck))
			{
				$currentStep = \App()->Request['step'] + 1;
				$this->definePartOfListingData(\App()->Request->getRequest(), $_FILES);
				$this->setMaxReachedStepsCount($currentStep);
			}
			else
			{
				$currentStep = \App()->Request['step'];
			}
		}
		elseif (\App()->Request['action_back'])
		{
			$currentStep = \App()->Request['step'] - 1;
            $this->setMaxReachedStepsCount($currentStep);
		}
		elseif (\App()->Request['action_go_to_step'])
		{
			$currentStep = \App()->Request['step'];
            $this->setMaxReachedStepsCount($currentStep);
		}
		else
		{
			$this->setAlreadyDefinedListingData(array()); // needed when manage_pictures performed from the first step
			$currentStep = 1;
		}

		// the current step should be in the range of [1...maxReachedStepsCount]
		$currentStep = min($currentStep, $this->getMaxReachedStepsCount());
		$currentStep = max($currentStep, 1);

		$this->setCurrentStep($currentStep);
		$this->saveState();
	}

	public function saveState()
	{
		\App()->Session->setValue('addListingLastState',array(
			'step' => $this->currentStep,
			'add_listing_session_id' => $this->sessionContainer->getId()
		));
	}

	public function saveListing()
	{
		/**
		 * @var \modules\classifieds\lib\Listing\Listing $listing
		 */
		$listing = $this->addListingFormObject->getObject();
		$listing->addLastUserIpProperty(\modules\ip_blocklist\lib\IpProcessor::getClientIpAsString());
		\App()->ListingManager->prepareReservedPlaceForListing($listing);

		$listing->addPicturesProperty();
		$listingGallery = \App()->ListingGalleryManager->createListingGallery();
		$listingGallery->setListingSID($listing->getSID());
		$listing->setPropertyValue('pictures', $listingGallery->getPicturesAmount());

		\App()->ListingManager->saveListing($listing);

		$afterAddListingActions = new \core\ExtensionPoint('modules\classifieds\apps\FrontEnd\IAfterAddListingAction');
		foreach ($afterAddListingActions as $afterAddListingAction)
		{
			/**
			 * @var \modules\classifieds\apps\FrontEnd\IAfterAddListingAction $afterAddListingAction
			 */
			$afterAddListingAction->setListing($listing);
			$afterAddListingAction->perform();
		}

		//as the some properties of the $listing were deleted, here we get the same listing with all the properties
		$listingWithAllProperties = \App()->ListingManager->getObjectBySID($listing->getSID());
		$this->setListing($listingWithAllProperties);
		$this->sessionContainer->reset();
	}

	private function getSingleCategory($categoriesInfo)
	{
		foreach($categoriesInfo as $cat) if ($cat['sid'] != 0 ) return $cat;
	}

	private function getAlreadyDefinedListingData()
	{
		return (array) \App()->ListingManager->getListingInfoBySID($this->listingReservedSid);
	}

	private function setAlreadyDefinedListingData($data)
	{
		\App()->ListingManager->updateListingPartially($this->addListingFormObject->getObject(), $data);
	}

	private function definePartOfListingData($data, $uploadedFiles)
	{
		$listing = $this->addListingFormObject->getObject();
		foreach ($uploadedFiles as $uploadedFileId => $uploadedFileInfo)
		{
			if (!is_null($property = $listing->getProperty($uploadedFileId)))
			{
				// getSQLValue() performs uploading of the file
				$data[$uploadedFileId] = $property->getSQLValue();
			}
		}
		$this->setAlreadyDefinedListingData(array_merge($this->getAlreadyDefinedListingData(), $data));
	}

	private function setMaxReachedStepsCount($count)
	{
        if ($count > $this->getMaxReachedStepsCount())
		    $this->sessionContainer->setValue('maxReachedSteps', $count);
	}

	private function getMaxReachedStepsCount()
	{
		return $this->sessionContainer->getValue('maxReachedSteps');
	}

	private $currentStep = 1;
	public function setCurrentStep($currentStep)
	{
		$this->currentStep = $currentStep;
	}

	private $steps = array();

	/**
	 * @param $params
	 * @param $content
	 * @param \Smarty_Internal_Template $template
	 * @param $repeat
	 * @return string
	 */
	public function registerStep($params, $content, $template, &$repeat)
	{
		if ($repeat)
		{
			return "";
		}
		$registeredStepNumber = count($this->steps) + 1;
		$params['current'] = ($registeredStepNumber == $this->currentStep);
		$this->steps[$registeredStepNumber] = $params;
		$content = ($registeredStepNumber == $this->currentStep) ? $content : '';
		if (!empty($content))
		{
			return sprintf('<div class="step" id="step%d">%s</div>', $this->currentStep, $content);
		}
		else
		{
			return $content;
		}
	}

	/**
	 * @param $params
	 * @param \Smarty_Internal_Template $template
	 */
	public function addListingForm($params, $template)
	{
		$templateProcessor = $template->smarty;

		$formTemplate = $params['formTemplate'];
		// важно!  $template->fetch($formTemplate) нужно вызвать перед определением количе�?тва в�?ех шагов
		$formContent = $templateProcessor->fetch($formTemplate);

		$templateProcessor->assign("id", $params['id']);
		$templateProcessor->assign("formContent", $formContent);
		$templateProcessor->assign("currentStep", $this->currentStep);
		$templateProcessor->assign("stepIsFirst", $this->currentStep == 1);
		$templateProcessor->assign("stepIsLast", $this->stepIsLast());
		$templateProcessor->assign("steps", $this->steps);
		$templateProcessor->display("add_listing_form.tpl");
	}

	private function stepIsLast()
	{
		$stepsCount = count($this->steps);
		return ($stepsCount == 0) ? true : $this->currentStep == $stepsCount;
	}

	/**
	 * @param \modules\smarty_based_template_processor\lib\TemplateProcessor $templateProcessor
	 */
	public function setTemplateProcessor($templateProcessor)
	{
		$this->templateProcessor = $templateProcessor;
	}

	/**
	 * @param \modules\users\lib\User\User $currentUser
	 */
	public function setCurrentUser($currentUser)
	{
		$this->currentUser = $currentUser;
	}

	public function getListingSid()
	{
		return $this->listing->getSID();
	}

	public function displayOptionsAppliedMessage()
	{
		$displayOptionsAppliedMessageAction = \App()->ClassifiedsActionsFactory->createDisplayOptionsAppliedMessageAction($this->logger, $this->listing, \App()->PageRoute->getPagePathById('user_listings') . '?id[equal]=' . $this->getListingSid());
		$displayOptionsAppliedMessageAction->perform();
	}

	/**
	 * @param \modules\classifieds\apps\FrontEnd\scripts\ManageListingOptionsHandlerLogger $logger
	 */
	public function setLogger($logger)
	{
		$this->logger = $logger;
	}
}

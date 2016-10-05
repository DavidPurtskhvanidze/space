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

class ManageListingOptionsHandlerActions
{
	/**
	 * @var ManageListingOptionsHandlerLogger
	 */
	private $logger;

	/**
	 * @var ManageListingOptionsHandlerContext
	 */
	private $context;

	public function displaySelectOptions()
	{
		$displaySelectOptionsAction = \App()->BasketActionsFactory->createDisplaySelectOptionsAction($this->context->getListing(), $this->context->getPredefinedRequestData());
		$displaySelectOptionsAction->perform();
	}

	public function deleteBasketItemsForListing()
	{
		$action = \App()->BasketActionsFactory->createDeleteBasketItemsForListing($this->context->getListingSid());
		$action->perform();
	}

	public function activateListingByUser()
	{
		$activateListingByUserAction = \App()->ClassifiedsActionsFactory->createActivateListingByUserAction($this->context->getListing());
		$activateListingByUserAction->perform();
		$this->logger->logListingActivated();
	}

	public function addActivationToBasket()
	{
		$addActivationToBasketAction = \App()->BasketActionsFactory->createAddActivationToBasketAction($this->context->getListingSid(), $this->context->getListingOwnerSid());
		$addActivationToBasketAction->perform();
		$this->logger->logListingActivationAddedToBasket();
	}

	public function addFreeOptionsToContainer()
	{
		$addOptionsToContainerAction = \App()->BasketActionsFactory->createAddOptionsToContainerAction($this->context->getListingSid(), $this->context->getFreeOptionIdsToAddToContainer());
		$addOptionsToContainerAction->perform();
		$this->logger->logOptionsAddedToContainer($this->context->getFreeOptionIdsToAddToContainer());
	}

	public function addPaidOptionsToBasket()
	{
		$addOptionsToBasketAction = \App()->BasketActionsFactory->createAddOptionsToBasketAction($this->context->getListingSid(), $this->context->getListingOwnerSid(), $this->context->getPaidOptionIdsToAddToBasket());
		$addOptionsToBasketAction->perform();
		$this->logger->logOptionsAddedToBasket($this->context->getPaidOptionIdsToAddToBasket());
	}

	public function activateFreeOptions()
	{
		$action = \App()->ClassifiedsActionsFactory->createActivateOptionsAction($this->context->getListing(), $this->context->getFreeOptionIdsToActivate());
		$action->perform();
		$this->logger->logOptionsActivated($this->context->getFreeOptionIdsToActivate());
	}

	public function displayOptionsAppliedMessage()
	{
		$url = \App()->PageRoute->getPagePathById('user_listings');
		if (\App()->Request['searchId'])
		{
			$url .= '?action=restore&searchId=' . \App()->Request['searchId'];
		}
		$displayOptionsAppliedMessageAction = \App()->ClassifiedsActionsFactory->createDisplayOptionsAppliedMessageAction($this->logger, $this->context->getListing(), $url);
		$displayOptionsAppliedMessageAction->perform();
	}
	
	public function setContext($context)
	{
		$this->context = $context;
	}

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}

	public function deleteContainerItemsForListing()
	{
		$action = \App()->BasketActionsFactory->createDeleteContainerItemsForListing($this->context->getListingSid());
		$action->perform();
	}

	public function performSelectOptionsProcess()
	{
		$process = \App()->ClassifiedsActionsFactory->createSelectListingOptionsProcess($this->context, $this);
		$process->perform();
	}

	public function assignPackage()
	{
		$action = \App()->ClassifiedsActionsFactory->createAssignPackageAction($this->context->getChosenPackageSid(), $this->context->getListing(), $this->context->getUserContract());
		$action->perform();
	}

	public function displayChoosePackage()
	{
		$action = \App()->ClassifiedsActionsFactory->createDisplayChoosePackageAction($this->context->getUserContract(), $this->context->getListing(), $this->context->getPredefinedRequestData());
		$action->perform();
	}

	public function displayNoActiveContractMessage()
	{
		$action = \App()->ClassifiedsActionsFactory->createDisplayNoActiveContractMessageAction();
		$action->perform();
	}
}

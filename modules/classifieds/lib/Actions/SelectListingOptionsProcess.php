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

class SelectListingOptionsProcess
{
	/**
	 * @var \modules\classifieds\apps\FrontEnd\scripts\ManageListingOptionsHandlerContext
	 */
	private $context;

	/**
	 * @var \modules\classifieds\apps\FrontEnd\scripts\ManageListingOptionsHandlerActions
	 */
	private $actions;

	public function perform()
	{
		if (!$this->context->areOptionsSelected())
		{
			$this->actions->displaySelectOptions();
		}
		elseif ($this->context->isListingActive())
		{
			$this->actions->deleteBasketItemsForListing();
			$this->actions->activateFreeOptions();
			$this->actions->addPaidOptionsToBasket();
			$this->actions->displayOptionsAppliedMessage();
		}
		elseif ($this->context->isListingNeverActivatedBefore())
		{
			$this->actions->deleteBasketItemsForListing();
			if ($this->context->isActivationFree())
			{
				$this->actions->activateListingByUser();

				if ($this->context->isListingActive())
				{
					$this->actions->activateFreeOptions();
					$this->actions->addPaidOptionsToBasket();
					$this->actions->displayOptionsAppliedMessage();
				}
				else
				{
					$this->actions->deleteContainerItemsForListing();
					$this->actions->addFreeOptionsToContainer();
					$this->actions->addPaidOptionsToBasket();
					$this->actions->displayOptionsAppliedMessage();
				}
			}
			else
			{
				$this->actions->addActivationToBasket();
				$this->actions->deleteContainerItemsForListing();
				$this->actions->addFreeOptionsToContainer();
				$this->actions->addPaidOptionsToBasket();
				$this->actions->displayOptionsAppliedMessage();
			}
		}
		elseif ($this->context->isListingExpired())
		{
			$this->actions->deleteBasketItemsForListing();
			if ($this->context->isActivationFree())
			{
				$this->actions->activateListingByUser();
				if ($this->context->isListingActive())
				{
					$this->actions->activateFreeOptions();
				}
				else
				{
					$this->actions->deleteContainerItemsForListing();
					$this->actions->addFreeOptionsToContainer();
				}
			}
			else
			{
				$this->actions->addActivationToBasket();
				$this->actions->deleteContainerItemsForListing();
				$this->actions->addFreeOptionsToContainer();
			}
			$this->actions->addPaidOptionsToBasket();
			$this->actions->displayOptionsAppliedMessage();
		}
		else
		{
			$this->actions->deleteBasketItemsForListing();
			$this->actions->activateListingByUser();
			if ($this->context->isListingActive())
			{
				$this->actions->activateFreeOptions();
			}
			else
			{
				$this->actions->deleteContainerItemsForListing();
				$this->actions->addFreeOptionsToContainer();
			}
			$this->actions->addPaidOptionsToBasket();
			$this->actions->displayOptionsAppliedMessage();
		}
	}

	public function setContext($context)
	{
		$this->context = $context;
	}

	public function setActions($actions)
	{
		$this->actions = $actions;
	}
}

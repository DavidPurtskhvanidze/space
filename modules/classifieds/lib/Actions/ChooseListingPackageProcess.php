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

/**
 * @property \modules\membership_plan\lib\Contract\Contract userContract
 */
class ChooseListingPackageProcess
{
	/**
	 * @var \modules\classifieds\apps\FrontEnd\scripts\ManageListingOptionsHandlerContext
	 */
	private $context;

	/**
	 * @var \modules\classifieds\apps\FrontEnd\scripts\ManageListingOptionsHandlerActions
	 */
	private $actions;

	function perform()
	{
		if ($this->context->isListingActive())
		{
			$this->actions->performSelectOptionsProcess();
		}
		elseif ($this->context->hasUserActiveContract())
		{
			if ($this->context->isListingNeverActivatedBefore())
			{
				$this->actions->performSelectOptionsProcess();
			}
			elseif ($this->context->isListingExpired())
			{
				if ($this->context->isPackageChosen())
				{
					$this->actions->assignPackage();
					$this->actions->performSelectOptionsProcess();
				}
				else
				{
					$this->actions->displayChoosePackage();
				}
			}
			else
			{
				$this->actions->performSelectOptionsProcess();
			}
		}
		else
		{
			$this->actions->displayNoActiveContractMessage();

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

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


namespace modules\membership_plan\lib;

class AutoExtendUserContractAction
{
	/**
	 * @var AutoExtendUserContractActionHelper
	 */
	private $helper;
	private $errors = array();

	function perform()
	{
		if ($this->helper->isContractExtensionPossible())
		{
			if ($this->helper->isFreeCriterion())
			{
				$this->helper->extendContract();
			}
			elseif ($this->helper->doesUserHaveEnoughFunds())
			{
				$this->helper->chargeUser();
				$this->helper->extendContract();
			}
			else
			{
				$this->helper->expireContract('NOT_ENOUGH_FUNDS_TO_EXTEND');
				$this->helper->sendAdminUserContractExpiredMessage();
			}
		}
		else
		{
			$this->helper->expireContract('CONTRACT_EXPIRED');
			$this->helper->sendAdminUserContractExpiredMessage();
		}
	}
	
	function canPerform()
	{
		if (is_null($this->helper->getContract()->getID()))
		{
			$this->errors[] = 'CONTRACT_DOES_NOT_EXIST';
			return false;
		}
		return true;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function setHelper($helper)
	{
		$this->helper = $helper;
	}
}

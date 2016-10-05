<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\lib\User;

class User extends \lib\ORM\Object
{
	var $user_group_sid;
	var $contract_id;
	var $activation_key   = null;
	var $verification_key = null;

	public function incorporateData($data)
	{
		if (isset($data['contract_sid'])) $this->setContractID($data['contract_sid']);
		$this->details->incorporateData($data);
	}
	
	function setUserGroupSID($user_group_sid)
	{
		$this->user_group_sid = $user_group_sid;
	}
	
	function getUserGroupSID()
	{
		return $this->user_group_sid;
	}
	
	function getContractID()
	{
		return $this->contract_id;
	}
	
	function hasContract()
	{
        $contract_info = \App()->ContractManager->getContractInfoBySID($this->contract_id);
		return !empty($contract_info);
	}
	
	function mayChooseContract()
	{
		// user can subscribe to the membership plan if he has no active contract
		if ( !$this->hasContract() )
		{
			return true;
		} 
		else
		{
			$contract = \App()->ContractManager->getContractBySID($this->getContractID());
			return $contract->isExpired();
		}
	}
	
	function setContractID($contract_id)
	{
		$this->contract_id = $contract_id;
	}

	function getUserName()
	{
		return $this->getPropertyValue('username');
	}

	function isSavedInDB()
	{
		$sid = $this->getSID();
		return !empty($sid);
	}

	function getActivationKey()
	{
		return $this->activation_key;
	}

	function getVerificationKey()
	{
		return $this->verification_key;
	}

	function createActivationKey()
	{
		$this->activation_key = $this->createUniqueKey();
	}

	function createVerificationKey()
	{
		$this->verification_key = $this->createUniqueKey();
	}

	function createUniqueKey()
	{
		$symbols = array_merge( range('a','z'), range('0','9') );
		shuffle($symbols);
		return join('', $symbols);
	}

	public function getValueForEncodingToJson()
	{
		$hash = parent::getValueForEncodingToJson();
		if (!empty($hash['DisplayEmail']['value']) && $hash['DisplayEmail']['value'] == 'false')
		{
			$hash['email']['value'] = null;
		}
		return $hash;
	}
}

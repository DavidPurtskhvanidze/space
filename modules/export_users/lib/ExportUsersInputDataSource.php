<?php
/**
 *
 *    Module: export_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19780, 2016-06-17 13:19:18
 *
 *    This file is part of the 'export_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_users\lib;

class ExportUsersInputDataSource implements \lib\DataTransceiver\IInputDatasource
{
	var $userSids = array();
	var $userManager;

	function setUserSids($userSids)
	{
		$this->userSids = $userSids;
	}
	
	function setUserManager($userManager)
	{
		$this->userManager = $userManager;
	}

	function getNext()
	{
		$userSid = array_shift($this->userSids);
		$user = $this->userManager->getObjectBySID($userSid);
		return $user;
	}
	
	function isEmpty()
	{
		return empty($this->userSids);
	}
}

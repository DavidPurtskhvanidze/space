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

class CurrentUserTemplateStructureLazyLoadAdapter implements \ArrayAccess
{
	public $currentUserStructure = null;

	public function __construct()
	{
		$this->buildCurrentUserStructure();
	}
	
	public function offsetGet($index)
	{
		return $this->currentUserStructure[$index];
	}

	public function offsetExists($index)
	{
		return isset($this->currentUserStructure[$index]);
	}

	public function offsetSet($index, $value)
	{
		throw new \Exception('This object is read only');
	}

	public function offsetUnset($index)
	{
		throw new \Exception('This object is read only');
	}

	public function __toString()
	{
		return $this->offsetGet('username');
	}

	private function buildCurrentUserStructure()
	{
		$currentUserInfo = \App()->UserManager->getCurrentUserInfo();
		if (is_null(\App()->UserManager->getUserInfoBySID($currentUserInfo['sid'])))
		{
			\App()->UserManager->logout();
		}

		if (\App()->UserManager->isUserLoggedIn())
		{
			\App()->UserManager->updateCurrentUserSession();
			$current_user_info = \App()->UserManager->createTemplateStructureForCurrentUser();

			$current_user_info['logged_in'] = true;

			$currentUserStructureInfoProviders = new \core\ExtensionPoint('modules\users\ICurrentUserStructureInfoProvider');
			foreach ($currentUserStructureInfoProviders as $currentUserStructureInfoProvider)
			{
				$current_user_info[$currentUserStructureInfoProvider->getKey()] = $currentUserStructureInfoProvider->getValue();
			}
		}
		else
			$current_user_info['logged_in'] = false;

		$this->currentUserStructure = $current_user_info;
	}

}

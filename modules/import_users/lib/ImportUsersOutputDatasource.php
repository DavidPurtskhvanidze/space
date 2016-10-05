<?php
/**
 *
 *    Module: import_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19788, 2016-06-17 13:19:38
 *
 *    This file is part of the 'import_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_users\lib;

use core\ExtensionPoint;
use lib\DataTransceiver\IOutputDatasource;

class ImportUsersOutputDatasource implements IOutputDatasource
{
	private $userManager;
    private $activateUser;
    private $listValuesProcessor;
	private $userValidator;
    private $acivateNotifications;

	public function setUserValidator($userValidator)
	{
		$this->userValidator = $userValidator;
        return $this;
	}
	
	public function setListValuesProcessor($listValuesProcessor)
	{
		$this->listValuesProcessor = $listValuesProcessor;
        return $this;
	}

	public function setActivateUser($activateUser)
	{
		$this->activateUser = $activateUser;
        return $this;
	}

	public function setUserManager($userManager)
	{
		$this->userManager = $userManager;
        return $this;
	}
	
	public function add($user)
	{
		$this->listValuesProcessor->processObject($user);
		$this->userManager->saveUser($user);
		if ($this->activateUser)
		{
			$this->userManager->activateUserByUserName($user->getPropertyValue('username'));
		}
        if ($this->acivateNotifications)
        {
            $userNotifications = new ExtensionPoint('modules\users\apps\FrontEnd\IUserNotification');
            foreach ($userNotifications as $userNotification)
            {
                $userNotification->setValue($user->getSid(), '1');
            }
        }
        return $this;
	}

	public function canAdd($user)
	{
		return $this->userValidator->isValid($user);
	}

	public function finalize()
	{
	}

	public function getErrors()
	{
		return $this->userValidator->getErrors();
	}

    /**
     * @param boolean $acivateNotifications
     * @return ImportUsersOutputDatasource
     */
    public function setAcivateNotifications($acivateNotifications)
    {
        $this->acivateNotifications = $acivateNotifications;

        return $this;
    }
}

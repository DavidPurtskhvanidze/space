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


namespace modules\users\apps\AdminPanel;

class UserExtraPropertySetterOnSearchUser implements \modules\users\apps\AdminPanel\IUserExtraPropertySetterOnSearchUser
{
	/**
	 * User object
	 * @var \modules\users\lib\User\User $user;
	 */
	private $user;
	
	public function setUser($user)
	{
		$this->user = $user;
	}

	public function perform()
	{
		$this->user->addProperty
		(
			array
			(
				'id' => 'numberOfListings',
				'caption' => 'Number of Listings',
				'type' => 'integer',
				'value' => \App()->ListingManager->getListingsCountByUserSID($this->user->getSID()),
			)
		);
	}
}

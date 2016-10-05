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


namespace modules\classifieds\apps\FrontEnd;

class DeleteListingValidator implements IDeleteListingValidator
{
	private $listingSid;

	public function setListingSid($listingSid)
	{
		$this->listingSid = $listingSid;
	}

	public function isValid()
	{
		$loggedUser = \App()->UserManager->getCurrentUserSID();
		$listingOwner = \App()->ListingManager->getUserSIDByListingSID($this->listingSid);
		if ($loggedUser !== $listingOwner)
		{
			\App()->ErrorMessages->addMessage('NOT_OWNER_OF_LISTING');
			return false;
		}
		return true;
	}
}

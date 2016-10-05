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


namespace modules\classifieds\lib\Calendar\Actions;

class BookListingAction
{
	var $validator = null;
	var $manager = null;
	
	function setManager(&$manager)
	{
		$this->manager = $manager;
	}

	function setValidator(&$validator)
	{
		$this->validator = $validator;
	}
	
	function canPerform()
	{
		return $this->validator->isValid();
	}
	
	function perform()
	{
		$this->manager->sendBookListingRequest($_REQUEST['sender_email'], $_REQUEST['sender_name'], $_REQUEST['listing_sid'], $_REQUEST['field_sid'], $_REQUEST['from'], $_REQUEST['to'], $_REQUEST['comment']);
	}
	
	function getErrors()
	{
		return $this->validator->getErrors();
	}
}


?>

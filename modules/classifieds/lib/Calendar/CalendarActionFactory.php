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


namespace modules\classifieds\lib\Calendar;

class CalendarActionFactory
{
	var $manager = null;
	var $validationFactory = null;

	function setCalendarManager(&$manager)
	{
		$this->manager = $manager;
	}
	
	function setValidationFactory(&$validationFactory)
	{
		$this->validationFactory = $validationFactory;
	}
	
	function &getAction()
	{
		$actionId = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'noAction';
		$action = $this->createAction($actionId);
		return $action;
	}

	function createAction($actionId)
	{
		switch($actionId)
		{
			case "add" :
								$action = new Actions\AddPeriodAction();
				$validator = $this->validationFactory->createAddPeriodValidator($_REQUEST);
				$action->setManager($this->manager);
				$action->setValidator($validator);
				break;
			case "book" :
								$action = new Actions\BookListingAction();
				$validator = $this->validationFactory->createBookPeriodValidator($_REQUEST);
				$action->setManager($this->manager);
				$action->setValidator($validator);
				break;
			case "preview_book" :
								$action = new Actions\PreviewBookAction();
				break;
			case "delete" :
								$action = new Actions\DeletePeriodAction();
				$validator = $this->validationFactory->createDeletePeriodValidator($_REQUEST);
				$action->setManager($this->manager);
				$action->setValidator($validator);
				break;
			case "noAction" :
								$action = new Actions\CheckArgumentsAction();
				$validator = $this->validationFactory->createCheckArgumentsAction($_REQUEST);
				$action->setValidator($validator);
				break;
			case "show_done_message" :
				$action = $this->createShowDoneMessage();
				break;
			default :
								$action = new Actions\UnknownAction();
		}
		return $action; 
	}
	
	function createShowDoneMessage()
	{
		$prev_action = isset($_REQUEST['prev_action']) ? $_REQUEST['prev_action'] : '';
				$action = new Actions\NoAction();

		switch(strtolower($prev_action))
		{
			case "addperiodaction" :
				$action->setErrors(array('PERIOD_ADDED'));
				\App()->SuccessMessages->addMessage('PERIOD_ADDED');
				break;
			case "booklistingaction" :
				$action->setErrors(array('BOOK_REQUEST_SENT'));
				\App()->SuccessMessages->addMessage('BOOK_REQUEST_SENT');
			break;
			case "deleteperiodaction" :
				$action->setErrors(array('PERIOD_DELETED'));
				\App()->SuccessMessages->addMessage('PERIOD_DELETED');
			break;
			case "checkargumentsaction" :
			break;
			default :
				$action->setErrors(array('SHOW_DONE_MESSAGE'));
		}

		return $action;
	}
}

?>

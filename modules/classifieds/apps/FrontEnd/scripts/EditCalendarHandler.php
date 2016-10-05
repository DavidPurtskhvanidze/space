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


namespace modules\classifieds\apps\FrontEnd\scripts;

class EditCalendarHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Edit Calendar';
	protected $moduleName = 'classifieds';
	protected $functionName = 'manage_calendar';
	protected $parameters = array('show_template', 'listing_type');

	public function respond()
	{

		$listing_sid = isset($_REQUEST['listing_sid']) ? $_REQUEST['listing_sid'] : null;
		$listing_for_template = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter(\App()->ListingManager->getObjectBySid($listing_sid));
		$field_sid = isset($_REQUEST['field_sid']) ? $_REQUEST['field_sid'] : null;
		$template_processor = \App()->getTemplateProcessor();

		$factory = \App()->ObjectMother->createCalendarActionFactory();
		$action = $factory->getAction();
		$formSubmitted = isset($_REQUEST['action']) && ($_REQUEST['action'] == 'book');
		$deleteProperties = array();
		if (\App()->Request['action'] == 'add')
		{
			$formSubmitted = true;
			$deleteProperties = array('sender_name', 'sender_email');
		}

		$form = $this->getFormForCalendar($deleteProperties);
		$form->registerTags($template_processor);
		$template_processor->assign("form_fields", $form->getFormFieldsInfo());

		if ((\App()->Request['action'] == 'delete' && $action->canPerform()) || ($formSubmitted && $form->isDataValid() && $action->canPerform()))
		{
			$action->perform();
			$action_class = \App()->ObjectMother->getReflectionObject($action)->getShortName();
			switch(strtolower($action_class))
			{
				case "addperiodaction" :
					\App()->SuccessMessages->addMessage('PERIOD_ADDED');
					break;
				case "booklistingaction" :
					$template_processor->assign('hideForm', true);
					\App()->SuccessMessages->addMessage('BOOK_REQUEST_SENT');
					break;
				case "deleteperiodaction" :
					\App()->SuccessMessages->addMessage('PERIOD_DELETED');
					break;
				case "checkargumentsaction" :
					break;
				default :
					\App()->SuccessMessages->addMessage('SHOW_DONE_MESSAGE');
			}
		}

		$calendar = \App()->ObjectMother->createCalendar($listing_sid, $field_sid);
		$data = $calendar->getPropertyVariablesToAssign();
		$template_processor->filterThenAssign($data);

		$this->assignWhenPresent(array('from' , 'to', 'comment', 'sender_email', 'sender_name'), $template_processor);

		$template_processor->assign("listing", $listing_for_template);

		$template = isset($_REQUEST['show_template']) ? $_REQUEST['show_template'] : "edit_calendar.tpl";
        $listingType = isset($_REQUEST['listing_type']) ? $_REQUEST['listing_type'] : 'Listing';

        $template_processor->assign('listingType', $listingType);
		$template_processor->display($template);

	}

	private function getFormForCalendar($propertiesToDelete = array())
	{
		$properties = array(
			array(
				'id' 		=> 'sender_name',
				'caption' 	=> 'Your name',
				'type' 		=> 'string',
				'is_required'	=> true
			),
			array(
				'id' 		=> 'sender_email',
				'caption' 	=> 'Your Email',
				'type' 		=> 'email',
				'is_required'	=> true
			),
			array(
				'id' 		=> 'from',
				'caption' 	=> 'Start date',
				'type' 		=> 'date',
				'is_required'	=> true
			),
			array(
				'id' 		=> 'to',
				'caption' 	=> 'End date',
				'type' 		=> 'date',
				'is_required'	=> true
			),
			array(
				'id' 		=> 'comment',
				'caption' 	=> 'Comment',
				'type' 		=> 'text',
				'input_template' => 'raw_textarea.tpl'

			)
		);


		foreach (array_keys($properties) as $key)
		{
			if (in_array($properties[$key]['id'], $propertiesToDelete) === true)
			{
				unset($properties[$key]);
				continue;
			}
			if (isset($_REQUEST[$properties[$key]['id']]))
				$properties[$key]['value'] = $_REQUEST[$properties[$key]['id']];
		}

		$obj = new \lib\ORM\Object();
		$objDetails = new \lib\ORM\ObjectDetails();
		$objDetails->setOrmObjectFactory(\App()->OrmObjectFactory);
		$objDetails->setDetailsInfo($properties);
		$objDetails->buildProperties();
		$obj->setDetails($objDetails);
		$form = new \lib\Forms\Form($obj);

		return $form;
	}

	function assignWhenPresent($fields, &$template_processor)
	{
		foreach($fields as $field)
		{
			if(isset($_REQUEST[$field]))
			{
				$template_processor->filterThenAssign($field, $_REQUEST[$field]);
			}
		}
	}
}

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


namespace modules\classifieds\lib;

class SendTellFriendFormMessageAction
{
	var $errors = array();
	var $userSid;
	var $listingSid;
	var $form;
	var $contactFormObject;
	var $template_processor;
	var $metaDataProvider;
	var $displayTemplate = "tell_friend.tpl";
	var $formSubmitted = false;
	var $fieldsToReset = array();

	function canPerform()
	{
		if (empty($this->listingSid))
		{
			$this->errors['LISTING_SID_IS_EMPTY'] = 1;
		}
		return empty($this->errors);
	}
	
	function perform()
	{
		$form_fields = $this->form->getFormFieldsInfo();
		$this->template_processor->assign("form_fields", $form_fields);

		$errors = array();
		$submitted_data = array();
		if ($this->formSubmitted && $this->form->isDataValid())
		{
			$properties = $this->contactFormObject->details->getProperties();
			foreach (array_keys($properties) as $i)
			{
				$property = $properties[$i];
				$submitted_data[$property->getID()] = $property->getValue();
			}

			$message_sent = \App()->EmailService->send($submitted_data['friend_email'], 'email_template:tell_friend', [
				'listing' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter(\App()->ListingManager->getObjectBySID($this->listingSid)),
				'submitted_data' => $submitted_data
            ]);

			if (!$message_sent)
			{
				$errors['CANNOT_SEND_MAIL_CHECK_EMAIL'] = 1;
			}
			else
			{
				foreach ($this->fieldsToReset as $fieldID)
				{
					$this->contactFormObject->setPropertyValue($fieldID, '');
				}
			}
			$this->template_processor->assign('message_sent', $message_sent);
			$this->template_processor->assign('ERRORS', $errors);
		}
		$this->template_processor->display($this->displayTemplate);
	}
	
	function setContactFormObject(&$contactFormObject)
	{
		$this->contactFormObject = $contactFormObject;
	}
	
	function setForm(&$form)
	{
		$this->form = $form;
	}
	
	function setTemplateProcessor(&$template_processor)
	{
		$this->template_processor = $template_processor;
	}
	
	function setMetaDataProvider(&$metaDataProvider)
	{
		$this->metaDataProvider = $metaDataProvider;
	}
	
	function setUserSid($userSid)
	{
		$this->userSid = $userSid;
	}
	
	function setListingSid($listingSid)
	{
		$this->listingSid = $listingSid;
	}
	
	function setDisplayTemplate($displayTemplate)
	{
		$this->displayTemplate = $displayTemplate;
	}
	
	function setFormSubmitted($formSubmitted)
	{
		$this->formSubmitted = $formSubmitted;
	}

	function setFieldsToReset($fieldsToReset)
	{
		$this->fieldsToReset = $fieldsToReset;
	}

	function getErrors()
	{
		return $this->errors;
	}
}

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


namespace modules\users\lib;

class SendUserContactFormMessageAction
{
	var $errors = array();
	var $userSid;
	var $form;
	var $contactFormObject;
	var $template_processor;
	var $displayTemplate = "contact_form.tpl";
	var $formSubmitted = false;
    var $requestData;

	function canPerform()
	{
		if (is_null($this->userSid))
		{
			$this->errors['PARAMETERS_MISSED'] = 1;
		}
		return empty($this->errors);
	}
	
	function perform()
	{
		$errors = array();
		$data = array();
		if ($this->formSubmitted && $this->form->isDataValid())
		{
			$properties = $this->contactFormObject->details->getProperties();
			foreach (array_keys($properties) as $i)
			{
				$property = $properties[$i];
				$data[$property->getID()] = $property->getValue();
			}

			$user_info = \App()->UserManager->getUserInfoBySID($this->userSid);
			$message_sent = \App()->EmailService->send($user_info['email'], 'email_template:contact_form_message', $data);

			if (!$message_sent)
			{
				$errors['CANNOT_SEND_MAIL'] = 1;
			}
			else
			{
				\App()->SuccessMessages->addMessage('MESSAGE_SENT', array(), 'miscellaneous');
			}
            if ($this->formSubmitted)
                $this->template_processor->assign('serialized_data', htmlspecialchars(serialize($this->requestData)));
			$this->template_processor->assign('message_sent', $message_sent);
			$this->template_processor->assign('errors', $errors);
			
		}
		else
		{
			$form_fields = $this->form->getFormFieldsInfo();
			$this->template_processor->assign("form_fields", $form_fields);
		}
        $listing_sid = isset($this->requestData['listing_sid']) ? $this->requestData['listing_sid'] : NULL;
        $this->template_processor->assign('listing_sid', $listing_sid);
        $this->template_processor->assign('user_sid', $this->userSid);
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
	
	function setUserSid($userSid)
	{
		$this->userSid = $userSid;
	}
	
	function setDisplayTemplate($displayTemplate)
	{
		$this->displayTemplate = $displayTemplate;
	}
	
	function setFormSubmitted($formSubmitted)
	{
		$this->formSubmitted = $formSubmitted;
	}

    function setRequestData($requestData)
	{
		$this->requestData = $requestData;
	}

	function getErrors()
	{
		return $this->errors;
	}
}

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

class SendContactSellerFormMessageAction
{
	var $userSid;
	var $listingSid;
	var $form;
	var $contactFormObject;
	var $template_processor;
	var $displayTemplate = "contact_seller.tpl";
	var $formSubmitted = false;

	function canPerform()
	{
        $canPerform = true;
		if (is_null($this->userSid))
		{
			$canPerform = false;
            \App()->ErrorMessages->addMessage('PARAMETERS_MISSED');
		}
		if (is_null($this->listingSid))
		{
            $canPerform = false;
            \App()->ErrorMessages->addMessage('LISTING_SID_IS_EMPTY');
		}
		return $canPerform;
	}
	
	function perform()
	{
		$submitted_data = [];
		if ($this->formSubmitted && $this->form->isDataValid())
		{
			$properties = $this->contactFormObject->details->getProperties();
			foreach (array_keys($properties) as $i)
			{
				$property = $properties[$i];
				$submitted_data[$property->getID()] = $property->getValue();
			}

			$listing = \App()->ListingManager->getObjectBySID($this->listingSid);
			$emailAddress = is_null($listing->getPropertyValue('user')) ? \App()->SettingsFromDB->getSettingByName('notification_email') : $listing->getPropertyValue('user')->getPropertyValue('email');
			$message_sent = \App()->EmailService->send($emailAddress, 'email_template:contact_seller', array(
				'listing' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($listing),
				'seller_request' => $submitted_data
			), isset($submitted_data['Email']) ? $submitted_data['Email'] : null);

			if (!$message_sent)
			{
                \App()->ErrorMessages->addMessage('CANNOT_SEND_MAIL', [], 'miscellaneous');
			}
			else
			{
				\App()->SuccessMessages->addMessage('MESSAGE_SENT', [], 'miscellaneous');
			}
			$this->template_processor->assign('message_sent', $message_sent);
		}
		else
		{
			$form_fields = $this->form->getFormFieldsInfo();
			$this->template_processor->assign("form_fields", $form_fields);
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
	
	function setUserSid($userSid)
	{
		$this->userSid = $userSid;
	}
	
	function setListingSid($listingSid)
	{
		if(empty($listingSid))
			$this->errors['LISTING_SID_IS_EMPTY'] = 1;	
			
		$this->listingSid= $listingSid;
	}
	
	function setDisplayTemplate($displayTemplate)
	{
		$this->displayTemplate = $displayTemplate;
	}
	
	function setFormSubmitted($formSubmitted)
	{
		$this->formSubmitted = $formSubmitted;
	}
}

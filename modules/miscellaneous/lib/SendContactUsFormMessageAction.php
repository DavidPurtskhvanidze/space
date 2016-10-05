<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class SendContactUsFormMessageAction
{
	/**
	 * @var \lib\Forms\Form
	 */
	var $form;
	/**
	 * @var \lib\ORM\Object
	 */
	var $contactFormObject;
	/**
	 * @var \modules\smarty_based_template_processor\lib\TemplateProcessor
	 */
	var $template_processor;
	var $formSubmitted = false;

	function perform()
	{
        $template = \App()->Request->getValueOrDefault('miscellaneous_template', 'miscellaneous^contact_form.tpl');
		$form_fields = $this->form->getFormFieldsInfo();
		$this->template_processor->assign("form_fields", $form_fields);

		$message_sent = false;
		if ($this->formSubmitted && $this->form->isDataValid())
		{
			$submitted_data = array();
			$properties = $this->contactFormObject->getProperties();
			foreach ($properties as $property)
			{
				$submitted_data[$property->getID()] = $property->getValue();
			}

			$message_sent = \App()->EmailService->sendToAdmin('email_template:admin_contact_form_message', $submitted_data, $submitted_data['email']);

			if (!$message_sent)
			{
				\App()->ErrorMessages->addMessage('CANNOT_SEND_MAIL_CHECK_EMAIL');
			}
		}

		$this->template_processor->assign('message_sent', $message_sent);
		$this->template_processor->assign('systemEmail', \App()->SettingsFromDB->getSettingByName('system_email'));
		$this->template_processor->assign('returnBackUri', \App()->Request['returnBackUri']);
		$this->template_processor->display($template);
	}
	
	function setContactFormObject($contactFormObject)
	{
		$this->contactFormObject = $contactFormObject;
	}
	
	function setForm($form)
	{
		$this->form = $form;
	}
	
	function setTemplateProcessor($template_processor)
	{
		$this->template_processor = $template_processor;
	}
	
	function setFormSubmitted($formSubmitted)
	{
		$this->formSubmitted = $formSubmitted;
	}
}

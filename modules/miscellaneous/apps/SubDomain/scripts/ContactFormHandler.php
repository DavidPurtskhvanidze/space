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


namespace modules\miscellaneous\apps\SubDomain\scripts;

class ContactFormHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Contact Form';
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'contact_form';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$contactUsFormObject = \App()->ObjectMother->createContactUsFormObject($_REQUEST);
		$captchaEnabled = \App()->SettingsFromDB->getSettingByName('captcha_in_contact_form');
		$form = \App()->ObjectMother->createForm($contactUsFormObject, array(), $captchaEnabled);
		$form->registerTags($template_processor);
		$formSubmitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'send_message');
		$form->setFormSubmitted($formSubmitted);

		$template = \App()->Request->getValueOrDefault('miscellaneous_template', 'miscellaneous^contact_form.tpl');
		$form_fields = $form->getFormFieldsInfo();
		$template_processor->assign("form_fields", $form_fields);

		$message_sent = false;
		if ($formSubmitted && $form->isDataValid())
		{
			$submitted_data = array();
			$properties = $contactUsFormObject->getProperties();
			foreach ($properties as $property)
			{
				$submitted_data[$property->getID()] = $property->getValue();
			}
			$emailTemplateObj = new \modules\subdomain\lib\Email\DealerContactFormMessage();
			$message_sent = \App()->EmailService->send((string)\App()->Dealer['profile']['email'],
				'email_template:' . $emailTemplateObj->getId(), $submitted_data, $submitted_data['email']);

			if (!$message_sent)
			{
				\App()->ErrorMessages->addMessage('CANNOT_SEND_MAIL_CHECK_EMAIL');
			}
		}

		$template_processor->assign('message_sent', $message_sent);
		$template_processor->assign('returnBackUri', \App()->Request['returnBackUri']);
		$template_processor->display($template);

	}
}

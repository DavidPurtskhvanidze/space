<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\main\apps\AdminPanel\scripts;

class EmailTemplatesEditHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'main';
	protected $functionName = 'email_templates_edit';

	public function respond()
	{
		if ($id = \App()->Request->getValueOrDefault('id'))
		{
			$template_processor = \App()->getTemplateProcessor();
			if (\App()->Request->getValueOrDefault('action') == 'save')
			{
				$success = \App()->EmailTemplateManager->save(
					\App()->Request->getValueOrDefault('id'),
					\App()->Request->getValueOrDefault('subject', 'Empty Subject'),
					\App()->Request->getValueOrDefault('body', 'Empty body'));
				if ($success)
					\App()->SuccessMessages->addMessage('CHANGES_HAVE_BEEN_SUCCESSFULLY_SAVED');
			}
			$emailTemplatesList = new \core\ExtensionPoint('modules\main\apps\AdminPanel\IEmailTemplatesList');
			foreach($emailTemplatesList as $emailTemplate)
			{
				if ($emailTemplate->getId() == $id)
				{
					$template_processor->assign('availableVariables', $emailTemplate->getAvailableVariables());
					break;
				}
			}
			$template_processor->assign('emailTemplate', \App()->EmailTemplateManager->getEmailTemplateById($id));
			$template_processor->display ("email_template_edit.tpl");
		}
	}

}

<?php
/**
 *
 *    Module: form_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: form_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19783, 2016-06-17 13:19:26
 *
 *    This file is part of the 'form_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\form_manager\apps\AdminPanel\scripts;
 
class AddFormHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'form_manager';
	protected $functionName = 'add_form';
	private $appId = 'FrontEnd';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$this->appId = \App()->Request->getValueOrDefault('application_id', 'FrontEnd');
		if (!\App()->doesAppExist($this->appId))
		{
			throw new \lib\Http\NotFoundException('Requested application "' . $this->appId . '" does not exist');
		}

		if(\App()->Request->getValueOrDefault('action') == "add")
		{
			if($form_id = \App()->Request->getValueOrDefault('form_id'))
			{
				if(in_array($form_id, \App()->FormManager->getFormIDs($this->appId)))
				{
               		\App()->ErrorMessages->addMessage('VALUE_EXISTS', array('fieldCaption' => "ID"));
				}
				else
				{
					$this->addForm($form_id, \App()->Request->getValueOrDefault('title'), \App()->Request->getValueOrDefault('category_sid'));
               		\App()->SuccessMessages->addMessage('FORM_ADDED');
               		$form_info = \App()->FormManager->getFormInfoByID($form_id, $this->appId);
					$urlParams = [
						'sid' => $form_info['sid'],
						'application_id' => $this->appId,
					];
					throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'edit_form') . "?" . http_build_query($urlParams));
				}
			}
			else
			{
                \App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => "ID"));
			}
			$templateProcessor->assign('category_sid', \App()->Request->getValueOrDefault('category_sid'));
			$templateProcessor->assign('form_id', \App()->Request->getValueOrDefault('form_id'));
		}
		$templateProcessor->assign('categories', \App()->CategoryManager->getAllCategoriesInfo());
		$templateProcessor->assign('application_id', $this->appId);
		$templateProcessor->display("add_form.tpl");
	}

	private function addForm($form_id, $title, $category_sid)
	{
		\App()->FormManager->addForm($form_id, $title, $category_sid, $this->appId);
	}
}

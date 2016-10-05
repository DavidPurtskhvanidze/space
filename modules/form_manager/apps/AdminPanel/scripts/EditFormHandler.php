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
 
class EditFormHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'form_manager';
	protected $functionName = 'edit_form';
	private $appId = 'FrontEnd';

	public function respond()
	{
		$this->appId = \App()->Request->getValueOrDefault('application_id', 'FrontEnd');
		if (!\App()->doesAppExist($this->appId))
		{
			throw new \lib\Http\NotFoundException('Requested application "' . $this->appId . '" does not exist');
		}

		if($sid = \App()->Request->getValueOrDefault('sid'))
		{
			$form_info = \App()->FormManager->getFormInfoBySID($sid);
			switch(\App()->Request->getValueOrDefault('action'))
			{
				case "add":
					$this->addField($form_info['sid'], \App()->Request->getValueOrDefault('field_id'), \App()->Request->getValueOrDefault('title'));
					break;
				case "delete":
					$this->deleteField($form_info['sid'], \App()->Request->getValueOrDefault('field_sid'));
					break;
				case "sort";
					$this->sort($form_info['sid'], \App()->Request->getValueOrDefault('sortingOrder'));
					break;	
				case "save":
					$this->saveField($form_info['sid'], \App()->Request->getValueOrDefault('title'));
					break;
			}
			$category_fields_info = \App()->ListingFieldManager->getListingFieldsInfoByCategory($form_info['category_sid']);
			$category_fields = array();
			foreach ($category_fields_info as $key => $value) 
			{
				$category_fields[$value['sid']] = $value['id'];
			}
			$category_fields[] = "pictures";
			$category_fields[] = "keywords";
			$fields_info = \App()->FormManager->getFieldsInfoByFormSid($form_info['sid']);
			foreach ($fields_info as $form_field) 
				foreach ($category_fields as $key => $category_field)
					if($category_field == $form_field['field_id'])
						unset($category_fields[$key]);
			$templateProcessor = \App()->getTemplateProcessor();
			$templateProcessor->assign('category_fields', $category_fields);
			$templateProcessor->assign('fields_info', $fields_info);
			$templateProcessor->assign('form_info', \App()->FormManager->getFormInfoBySID($sid));
			$templateProcessor->assign('application_id', $this->appId);
			$templateProcessor->display("edit_form.tpl");
		}
	}

	private function addField($form_sid, $field_id, $caption = null)
	{
		if(!\App()->FormManager->getFieldInfo($form_sid, $field_id) || in_array($field_id, array('Fieldset')))
		{
			\App()->FormManager->addField($form_sid, $field_id, $caption);
            \App()->SuccessMessages->addMessage('FIELD_ADDED');
		}
	}

	private function deleteField($form_sid, $sid)
	{
		\App()->FormManager->deleteField($form_sid, $sid);
        \App()->SuccessMessages->addMessage('FIELD_DELETED');
	}

	private function saveField($form_sid, $title)
	{
		\App()->FormManager->saveField($form_sid, $title);
        \App()->SuccessMessages->addMessage('FORM_SAVED');
	}

	private function sort($form_sid, $fields)
	{
		$object_replacer = new \modules\form_manager\lib\FormFieldsReplacer();
		$object_replacer->setNewOrder($fields);
		$object_replacer->setParentValue($form_sid);
		$object_replacer->update();
		die();
	}
}

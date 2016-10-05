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


namespace modules\miscellaneous\apps\AdminPanel\scripts;

class EditCustomSettingHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'edit_custom_setting';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$setting_sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;
		$errors = array();
		$field_errors = array();
		$custom_setting_info = null;

		if (\App()->CustomSettings->doesSettingExistBySID($setting_sid))
		{
			$custom_setting_info = \App()->CustomSettings->getSettingInfoBySid($setting_sid);

			if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save')
			{
				$setting_new_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : null;
				$setting_new_value = isset($_REQUEST['value']) ? $_REQUEST['value'] : null;

				$canPerform = true;
				$validators = new \core\ExtensionPoint('modules\miscellaneous\apps\AdminPanel\IEditCustomSettingValidator');
				foreach ($validators as $validator)
				{
					$validator->setSid($setting_sid);
					$validator->setNewId($setting_new_id);
					$validator->setNewValue($setting_new_value);
					$canPerform &= $validator->isValid();
				}

				$canPerform &= \App()->CustomSettings->maySettingBeUpdated($setting_sid, $setting_new_id, $setting_new_value);

				if ($canPerform)
				{
					\App()->CustomSettings->updateSetting($setting_sid, $setting_new_id, $setting_new_value);
					throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'custom_settings'));
				}
				else
				{
					$custom_setting_info = array('sid' => $setting_sid, 'id' => $setting_new_id, 'value' => $setting_new_value);
				}
			}
			$template_processor->assign("custom_setting_info", $custom_setting_info);
		}
		else
		{
			\App()->ErrorMessages->addMessage('WRONG_PARAMETERS_SPECIFIED');
			$template_processor->assign("renderForm", false);
		}

		$template_processor->assign("field_errors", $field_errors);
		$template_processor->display("edit_custom_setting.tpl");
	}
}

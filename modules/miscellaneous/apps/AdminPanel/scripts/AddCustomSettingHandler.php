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

// version 5 wrapper header

class AddCustomSettingHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'add_custom_setting';

	public function respond()
	{
		
// end of version 5 wrapper header

	
	$template_processor = \App()->getTemplateProcessor();

	$errors = array();

	$custom_setting_info = null;
	
	if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add') 
	{
		$new_setting_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : null;
		$new_setting_value  = isset($_REQUEST['value']) ? $_REQUEST['value'] : null;
		
		$may_be_added = \App()->CustomSettings->maySettingBeAdded($new_setting_id, $new_setting_value);
		
		if ($may_be_added)
		{
			\App()->CustomSettings->addSetting($new_setting_id, $new_setting_value);
			throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'custom_settings'));
		} else 
		{
			$custom_setting_info = array('id' => $new_setting_id, 'value' => $new_setting_value);
		}
	}

	$template_processor->assign("custom_setting_info", $custom_setting_info);

	$template_processor->display("add_custom_setting.tpl");
//  version 5 wrapper footer

	}
}
// end of version 5 wrapper footer
?>

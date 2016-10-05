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

class CustomSettingsHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\miscellaneous\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'custom_settings';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		if (\App()->Request['action'] == 'delete')
		{
			$this->deleteSetting(\App()->Request['sid']);
			throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI());
		}

		$customSettingsInfo = \App()->CustomSettings->getSettingsInfo();
		$templateProcessor->assign("custom_settings", $customSettingsInfo);
		$templateProcessor->display("custom_settings.tpl");
	}

	private function deleteSetting($sid)
	{
		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\miscellaneous\apps\AdminPanel\IDeleteCustomSettingValidator');
		foreach ($validators as $validator)
		{
			$validator->setSid($sid);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;

		\App()->CustomSettings->deleteSettingBySID($sid);
	}

	public function getCaption()
	{
		return "Custom Settings";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array
		(
			\App()->PageRoute->getSystemPageURL($this->moduleName, 'add_custom_setting'),
			\App()->PageRoute->getSystemPageURL($this->moduleName, 'edit_custom_setting'),
		);
	}

	public static function getOrder()
	{
		return 400;
	}
}

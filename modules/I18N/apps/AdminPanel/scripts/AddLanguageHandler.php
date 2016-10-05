<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\apps\AdminPanel\scripts;

class AddLanguageHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'I18N';
	protected $functionName = 'add_language';

	public function respond()
	{

		$errors = array();
		$params = array();
		$languageActionFactory = new \modules\I18N\lib\Actions\LanguageActionFactory();
		if (isset($_REQUEST['action']))
		{
			$action_name = $_REQUEST['action'];
			$params = $_REQUEST;

			$action = $languageActionFactory->get($action_name, $params);

			if ($action->canPerform())
			{
				$action->perform();
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'manage_languages'));
			}
		}

		$mobile_themes = array();
		if (\App()->doesAppExist('MobileFrontEnd'))
		{
			$mobileFrontEndThemeManager = new \modules\template_manager\lib\ThemeManager("MobileFrontEnd");
			$mobile_themes = $mobileFrontEndThemeManager->getThemesList();
		}
		$frontEndThemeManager = new \modules\template_manager\lib\ThemeManager("FrontEnd");
		$themes = $frontEndThemeManager->getThemesList();
		$adminPanelThemeManager = new \modules\template_manager\lib\ThemeManager("AdminPanel");
		$admin_themes = $adminPanelThemeManager->getThemesList();

		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('themes', $themes);
		$template_processor->assign('admin_themes', $admin_themes);
		$template_processor->assign('mobile_themes', $mobile_themes);
		$template_processor->assign('request_data', $params);
		$template_processor->display('add_language.tpl');
	}
}

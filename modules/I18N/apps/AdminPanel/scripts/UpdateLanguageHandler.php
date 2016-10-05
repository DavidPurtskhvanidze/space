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

class UpdateLanguageHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\I18N\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'I18N';
	protected $functionName = 'edit_language';

	public function respond()
	{

		$errors = array();
		$params = array();
		$lang_id = isset($_REQUEST['languageId']) ? $_REQUEST['languageId'] : null;

		$i18n = \App()->I18N;

		$languageActionFactory = new \modules\I18N\lib\Actions\LanguageActionFactory();

		if ($i18n->languageExists($lang_id))
		{
			$params = $i18n->getLanguageData($lang_id);
			$params['languageId'] = $lang_id;
			if (isset($_REQUEST['action']))
			{
				$action_name = $_REQUEST['action'];

				$params = array_merge($params, $_REQUEST);

				$action = $languageActionFactory->get($action_name, $params);

				if ($action->canPerform())
				{
					$action->perform();
					throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'manage_languages'));
				}
			}
		}
		else \App()->ErrorMessages->addMessage('LANGUAGE_DOES_NOT_EXIST');

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
		$template_processor->assign('lang', $params);
		$template_processor->display('update_language.tpl');
	}

	public function getCaption()
	{
		return "Manage Languages";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, 'manage_languages');
	}

	public function getHighlightUrls()
	{
		return array
		(
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'add_language'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'edit_language'),
		);
	}

	public static function getOrder()
	{
		return 100;
	}
}

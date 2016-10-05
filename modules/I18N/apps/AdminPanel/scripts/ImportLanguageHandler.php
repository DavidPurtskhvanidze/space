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

class ImportLanguageHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\I18N\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'I18N';
	protected $functionName = 'import_language';

	public function respond()
	{
		$languageActionFactory = new \modules\I18N\lib\Actions\LanguageActionFactory();

		if (isset($_REQUEST['action']))
		{
			$action_name = $_REQUEST['action'];
			$params = $_REQUEST + $_FILES['lang_file'];

			$action = $languageActionFactory->get($action_name, $params);

			if ($action->canPerform())
			{
				$action->perform();
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'manage_languages'));
			}
		}

		$template_processor = \App()->getTemplateProcessor();
		$template_processor->display('import_language.tpl');
	}

	public function getCaption()
	{
		return "Import Language";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array();
	}

	public static function getOrder()
	{
		return 300;
	}
}

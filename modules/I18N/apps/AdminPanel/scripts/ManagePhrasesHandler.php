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

class ManagePhrasesHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\I18N\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'I18N';
	protected $functionName = 'manage_phrases';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$phraseActionFactory = new \modules\I18N\lib\Actions\PhraseActionFactory();
		if (isset($_REQUEST['action']))
		{
			$action_name = $_REQUEST['action'];
			$params = $_REQUEST;

			$action = $phraseActionFactory->get($action_name, $params, $template_processor);

			if ($action->canPerform())
			{
				$action->perform();
			}
		}
		else
		{
			$template_processor->assign('criteria', $_REQUEST);
		}

		$i18n = \App()->I18N;

		$domains = $i18n->getDomainsData();
		$languages = $i18n->getLanguagesData();

		$template_processor->assign('domains', $domains);
		$template_processor->assign('languages', $languages);
		$template_processor->display('manage_phrases.tpl');
	}

	public function getCaption()
	{
		return "Manage Phrases";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array
		(
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'add_phrase'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'edit_phrase'),
		);
	}

	public static function getOrder()
	{
		return 200;
	}
}

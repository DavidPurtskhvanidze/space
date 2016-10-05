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

class AddPhraseHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'I18N';
	protected $functionName = 'add_phrase';

	public function respond()
	{

		$errors = array();
		$params = array();

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
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'manage_phrases') . '?action=remember_previous_state');
			}
		}

		$i18n = \App()->I18N;

		$domains = $i18n->getDomainsData();
		$langs = $i18n->getLanguagesData();

		$template_processor->assign('domains', $domains);
		$template_processor->assign('langs', $langs);
		$template_processor->assign('request_data', $_REQUEST);
		$template_processor->display('add_phrase.tpl');
	}
}

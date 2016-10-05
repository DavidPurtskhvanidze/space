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

class AutoTranslateHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'I18N';
	protected $functionName = 'auto_translate';

	public function respond()
	{
		$errors = array();
		$template_processor = \App()->getTemplateProcessor();
		
		if (isset($_REQUEST['action']))
		{
			$action_name = $_REQUEST['action']; 
			$action = \App()->PhraseActionFactory->get($action_name, $_REQUEST, $template_processor);
			if ($action->canPerform())
			{
				$action->perform();
				$template_processor->display('refresh_opener_and_close_popup.tpl');
				return;
			}
		}
		
		$phrase_id = isset($_REQUEST['phrase']) ? $_REQUEST['phrase'] : null;
		$domain_id = isset($_REQUEST['domain']) ? $_REQUEST['domain'] : null;
		
		$i18n = \App()->I18N;
		
		$langs = $i18n->getLanguagesData();
		$template_processor->assign('langs', $langs);
		
		if (!$i18n->phraseExists($phrase_id, $domain_id))
		{
			$domains = $i18n->getDomainsData();
			$template_processor->assign('domains', $domains);
			$template_processor->assign('request_data', $_REQUEST);
			$template_processor->display('add_phrase.tpl');
		}
		else
		{
			$phrase_data = $i18n->getPhraseData($phrase_id, $domain_id);
			$template_processor->assign('phrase', $phrase_data);
			$template_processor->display('update_phrase.tpl');
		}
	}
}

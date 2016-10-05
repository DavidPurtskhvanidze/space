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

class UpdatePhraseHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'I18N';
	protected $functionName = 'edit_phrase';

	public function respond()
	{
		$phrase_data = array();

		$phrase_id = !is_null(\App()->Request['phrase']) ? urldecode(\App()->Request['phrase']) : null;
		$domain_id = \App()->Request['domain'];
		$lang_id = \App()->Request['lang'];
		$template_processor = \App()->getTemplateProcessor();

		$errors = array();
		if (\App()->I18N->phraseExists($phrase_id, $domain_id))
		{
			$phrase_data = \App()->I18N->getPhraseData($phrase_id, $domain_id);

			if (!empty(\App()->Request['action']))
			{
				$action = \App()->PhraseActionFactory->get(\App()->Request['action'], \App()->Request->getRequest(), $template_processor);
				if ($action->canPerform())
				{
					$action->perform();
					if (\App()->Request['performActionAndStop']) die();
					throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'manage_languages') . '?action=remember_previous_state');
				}
				else
				{
					$phrase_data = array_merge($phrase_data, \App()->Request->getRequest());
				}
			}
		}
		else
		{
			\App()->ErrorMessages->addMessage('PHRASE_NOT_EXISTS');
		}

		if (!empty($errors) && \App()->Request['performActionAndStop'])
		{
			throw new \lib\Http\ForbiddenException($template_processor->fetch("string:{display_error_messages}"));
		}

		$template_processor->assign('phrase', $phrase_data);
		$template_processor->assign('domains', \App()->I18N->getDomainsData());
		$template_processor->assign('langs', \App()->I18N->getLanguagesData());
		$template_processor->assign('chosen_lang', $lang_id);
		$template_processor->display('update_phrase.tpl');
	}
}

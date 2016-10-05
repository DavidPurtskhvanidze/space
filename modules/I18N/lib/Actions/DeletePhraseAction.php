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


namespace modules\I18N\lib\Actions;

class DeletePhraseAction
{
	function __construct($i18n, $phrase, $domain)
	{
		$this->i18n = $i18n;
		$this->phrase = $phrase;
		$this->domain = $domain;
	}

	function canPerform()
	{
		$canPerform = true;

		if(!$this->i18n->phraseExists($this->phrase, $this->domain))
		{
			\App()->ErrorMessages->addMessage('PHRASE_DOES_NOT_EXIST');
			$canPerform = false;
		}

		/**
		 * @var \modules\I18N\apps\AdminPanel\IDeletePhraseValidator[] $validators
		 */
		$validators = new \core\ExtensionPoint('modules\I18N\apps\AdminPanel\IDeletePhraseValidator');
		foreach ($validators as $validator)
		{
			$validator->setPhraseData($this->domain, $this->phrase);
			$canPerform &= $validator->isValid();
		}

		return $canPerform;
	}

	function perform()
	{
		$this->i18n->deletePhrase($this->phrase, $this->domain);
	}
}

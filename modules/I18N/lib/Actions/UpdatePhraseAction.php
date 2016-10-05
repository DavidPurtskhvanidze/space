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

class UpdatePhraseAction
{
	function __construct($i18n, $phrase_data)
	{
		$this->i18n = $i18n;
		$this->phrase_data = $phrase_data;
	}

	function canPerform()
	{
		$canPerform = true;

		/**
		 * @var \modules\I18N\apps\AdminPanel\IEditPhraseValidator[] $validators
		 */
		$validators = new \core\ExtensionPoint('modules\I18N\apps\AdminPanel\IEditPhraseValidator');
		foreach ($validators as $validator)
		{
			$validator->setPhraseData($this->phrase_data);
			$canPerform &= $validator->isValid();
		}

		$translations = array(
			'phraseId' => $this->phrase_data['phrase'],
			'domainId' => $this->phrase_data['domain'],
			'translations' => array()
		);

		foreach ($this->phrase_data['translations'] as $k => $v)
		{
			$translations['translations'][] = array('LanguageId' => $k, 'Translation' => $v);
		}

		return $canPerform && $this->i18n->createUpdateTranslationValidator($translations)->isValid();
	}

	function perform()
	{
		return $this->i18n->updatePhrase($this->phrase_data);
	}
}

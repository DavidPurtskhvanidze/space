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

class DeleteLanguageAction
{
	function __construct($i18n, $lang_id)
	{
		$this->i18n = $i18n;
		$this->lang_id = $lang_id;
	}

	function canPerform()
	{
		$canPerform = true;

		/**
		 * @var \modules\I18N\apps\AdminPanel\IDeleteLanguageValidator[] $validators
		 */
		$validators = new \core\ExtensionPoint('modules\I18N\apps\AdminPanel\IDeleteLanguageValidator');
		foreach ($validators as $validator)
		{
			$validator->setLanguageId($this->lang_id);
			$canPerform &= $validator->isValid();
		}
		return $canPerform && $this->i18n->createDeleteLanguageValidator($this->lang_id)->isValid();
	}

	function perform()
	{
		$this->i18n->deleteLanguage($this->lang_id);
	}
}

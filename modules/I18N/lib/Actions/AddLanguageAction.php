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

class AddLanguageAction
{
	/**
	 * i18n
	 * @var \modules\I18N\lib\I18N
	 */
	private $i18n;
	/**
	 * $lang_data
	 * @var array
	 */
	private $lang_data;
	/**
	 * Validator batch
	 * @var \lib\Validation\ValidatorBatch
	 */
	private $validator;
	
	function __construct($i18n, $lang_data)
	{
		$this->i18n = $i18n;
		$this->lang_data = $lang_data;
		$this->validator = $this->i18n->createAddLanguageValidator($this->lang_data);
	}
	
	function canPerform()
	{
		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\I18N\apps\AdminPanel\IAddLanguageValidator');
		foreach ($validators as $validator)
		{
			$validator->setLangData($this->lang_data);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform)
		{
			return false;
		}
		// ToDo: refactor old validators so they can be used in maner of extension point
		return $this->validator->isValid();
	}
	
	function perform()
	{
		return $this->i18n->addLanguage($this->lang_data);
	}
}

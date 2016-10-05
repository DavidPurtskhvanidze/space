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

class SetDefaultLanguageAction
{
	function __construct($i18n, $lang_id)
	{
		$this->i18n = $i18n;
		$this->lang_id = $lang_id;
	}

	function canPerform()
	{
		return $this->i18n->createSetDefaultLanguageValidator($this->lang_id)->isValid();
	}

	function perform()
	{
		$this->i18n->setDefaultLanguage($this->lang_id);
	}
}

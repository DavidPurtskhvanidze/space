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

class UpdateLanguageAction
{
	function __construct($i18n, $lang_data)
	{
		$this->i18n = $i18n;
		$this->lang_data = $lang_data;
	}

	function canPerform()
	{
		$validator = $this->i18n->createUpdateLanguageValidator($this->lang_data);
		return $validator->isValid();
	}

	function perform()
	{
		return $this->i18n->updateLanguage($this->lang_data);
	}
}

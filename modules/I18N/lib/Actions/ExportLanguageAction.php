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

class ExportLanguageAction
{
	function __construct($i18n, $lang_id)
	{
		$this->i18n = $i18n;
		$this->lang_id = $lang_id;
	}

	function canPerform()
	{
		if (!$this->i18n->languageExists($this->lang_id))
		{
			\App()->ErrorMessages->addMessage('LANGUAGE_NOT_EXISTS');
			return false;
		}
		return true;
	}

	function perform()
	{
		$wrappedFunctions = new \core\WrappedFunctions();
		$filePath = $this->i18n->getFilePathToLangFile($this->lang_id);
		$fileBaseName = $wrappedFunctions->basename($filePath);
		$wrappedFunctions->header("Content-Type: application/download");
		$wrappedFunctions->header("Content-disposition: attachment; filename=" . $fileBaseName);
		$wrappedFunctions->readfile($filePath);
	}
}

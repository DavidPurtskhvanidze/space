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

class LanguageActionFactory
{
	function get($action, $params)
	{
		$i18n = \App()->I18N;
		$lang = isset($params['languageId']) ? $params['languageId'] : null;
		switch ($action)
		{
			case "set_default_language": 
				return new SetDefaultLanguageAction($i18n, $lang);
				break;
			case "add_language": 
				return new AddLanguageAction($i18n, $params);
				break;
			case "update_language": 
				return new UpdateLanguageAction($i18n, $params);
				break;
			case "delete_language": 
				return new DeleteLanguageAction($i18n, $lang);
				break;
			case "import_language": 
				return new ImportLanguageAction($i18n, $params, PATH_TO_ROOT . \App()->SystemSettings['TempFilesDir']);
				break;
			case "export_language": 
				return new ExportLanguageAction($i18n, $lang);
				break;
			default: 
				return new LanguageAction();
		}
	}
}

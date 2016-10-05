<?php
/**
 *
 *    Module: smarty_based_template_processor v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: smarty_based_template_processor-7.5.0-1
 *    Tag: tags/7.5.0-1@19835, 2016-06-17 13:21:56
 *
 *    This file is part of the 'smarty_based_template_processor' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\smarty_based_template_processor\lib;

class ThemeFilesUrlPlugin implements IPlugin
{
	static $files = array();
	const MODULE_NAME_SEPARATOR = "^";

	public function getPluginType()
	{
		return "function";
	}

	public function getPluginTag()
	{
		return "url";
	}

	public function getPluginCallback()
	{
		return array($this, 'getFileUrl');
	}

	public function getFileUrl($params, $templateProcessor)
	{
		$fileId = isset($params['file']) ? $params['file'] : null;
		if (!isset(self::$files[$fileId]))
		{
			list($fileId, $moduleName) = array_reverse(explode(self::MODULE_NAME_SEPARATOR, $fileId, 2));
			if (empty($moduleName)) $moduleName = $templateProcessor->getModuleName();
			self::$files[$fileId] = $templateProcessor->getTheme()->getFileUrl($moduleName, $fileId);
		}
		return self::$files[$fileId];
	}
}

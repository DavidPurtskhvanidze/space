<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace apps\AdminPanel;

class Theme extends \apps\AbstractTheme implements ITheme
{
	protected $name = 'default';

	public function getPathToTemplate($moduleName, $templateName)
	{
		return PATH_TO_ROOT . \App()->SystemSettings['ModulesDir'] . "$moduleName/apps/AdminPanel/templates/$templateName";
	}

	public function getFilePath($moduleName, $fileId)
	{
		return PATH_TO_ROOT . \App()->SystemSettings['ModulesDir'] . "$moduleName/apps/AdminPanel/templates/_files/$fileId";
	}

	public function getPathToModuleTemplatesDir($moduleName)
	{
		return PATH_TO_ROOT . \App()->SystemSettings['ModulesDir'] . "$moduleName/apps/AdminPanel/templates/";
	}
}

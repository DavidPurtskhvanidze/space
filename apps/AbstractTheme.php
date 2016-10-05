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


namespace apps;

abstract class AbstractTheme implements \apps\ITheme
{
	protected $name;
	protected $moduleName;
	protected $readOnly = false;
	protected $parentTheme;
	protected $parentThemeName;
	protected $fileNotFoundAction;

	public function getParentTheme()
	{
		return $this->parentTheme;
	}

	public function hasParentTheme()
	{
		return !is_null($this->parentTheme);
	}

	public function getName()
	{
		return $this->name;
	}

	public function getModuleName()
	{
		return $this->moduleName;
	}

	public function isReadOnly()
	{
		return $this->readOnly;
	}

	public function getPathToThemeDir()
	{
		return PATH_TO_ROOT . \App()->SystemSettings['ModulesDir'] . $this->getModuleName() . "/";
	}

	public function getPathToModuleTemplatesDir($moduleName)
	{
		return $this->getPathToThemeDir() . $moduleName . "/";
	}

	public function getPathToTemplate($moduleName, $templateName)
	{
		return $this->getPathToModuleTemplatesDir($moduleName) . $templateName;
	}

	public function getPathToFilesDir($moduleName)
	{
		return PATH_TO_ROOT . \App()->SystemSettings['ModulesDir'] . "{$this->getModuleName()}/$moduleName/_files/";
	}

	public function getFilePath($moduleName, $fileId)
	{
		return $this->getPathToFilesDir($moduleName) . $fileId;
	}

	public function getTemplate($moduleName, $templateName)
	{
		$pathToTemplate = $this->getPathToTemplate($moduleName, $templateName);

		if (\App()->FileSystem->file_exists($pathToTemplate))
		{
			return \App()->TemplateFactory->getTemplate($pathToTemplate, $this->name, $moduleName, $templateName);
		}

		if ($this->hasParentTheme())
		{
			return $this->parentTheme->getTemplate($moduleName, $templateName);
		}

		throw new \modules\smarty_based_template_processor\lib\TemplateNotFoundException("$moduleName:$templateName");
	}

    public function getFileContent($moduleName, $fileName)
    {
        $pathToFile = $this->getFilePath($moduleName, $fileName);
        if (\App()->FileSystem->file_exists($pathToFile))
        {
            return \App()->FileSystem->getContentsOfFile($pathToFile);
        }
    }

	public function getFileUrl($moduleName, $fileId)
	{
		$filePath = $this->getFilePath($moduleName, $fileId);

		if (\App()->FileSystem->file_exists($filePath))
		{
			$siteUrl = \App()->SystemSettings['SiteUrl'];
			return "{$siteUrl}/$filePath";
		}

		if ($this->hasParentTheme())
		{
			return $this->parentTheme->getFileUrl($moduleName, $fileId);
		}

		$this->fileNotFoundAction->execute();
	}

	public function setFileNotFoundAction($fileNotFoundAction)
	{
		$this->fileNotFoundAction = $fileNotFoundAction;
	}

	public function getParentThemeName()
	{
		return $this->parentThemeName;
	}

	public function setParentTheme($parentTheme)
	{
		$this->parentTheme = $parentTheme;
	}

	public function setModuleName($moduleName)
	{
		$this->moduleName = $moduleName;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setParentThemeName($parentThemeName)
	{
		$this->parentThemeName = $parentThemeName;
	}

	public function setReadOnly($readOnly)
	{
		$this->readOnly = $readOnly;
	}
}

<?php
/**
 *
 *    Module: template_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: template_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19839, 2016-06-17 13:22:09
 *
 *    This file is part of the 'template_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\template_manager\lib;

class ModuleTemplateManager
{
	private $appId;
	private $moduleName;
	private $themeManager;
	private $error = array();

	public function __construct($appId, $moduleName)
	{
		$this->appId = $appId;
		$this->moduleName = $moduleName;
	}

	public function setThemeManager($themeManager)
	{
		$this->themeManager = $themeManager;
	}
	public function getThemeManager()
	{
		return $this->themeManager;
	}

	private function doesTemplateFileExist($templateName)
	{
		return \App()->FileSystem->file_exists($this->getPathToModuleTemplate($templateName));
	}
	private function isTemplateFileWritable($templateName)
	{
		return \App()->FileSystem->is_writable($this->getPathToModuleTemplate($templateName));
	}

	private function doesModuleTemplatesDirExist()
	{
		return \App()->FileSystem->file_exists($this->getPathToModuleTemplatesDir());
	}
	private function isModuleTemplatesDirWritable()
	{
		return \App()->FileSystem->is_writable($this->getPathToModuleTemplatesDir());
	}
	private function isThemeDirWritable()
	{
		return \App()->FileSystem->is_writable($this->getPathToThemeDir());
	}


	private function getPathToModuleTemplatesDir()
	{
		return $this->themeManager->getApplicationCurrentTheme()->getPathToModuleTemplatesDir($this->moduleName);
	}
	private function getPathToModuleTemplate($templateName)
	{
		return $this->themeManager->getApplicationCurrentTheme()->getPathToTemplate($this->moduleName, $templateName);
	}
	private function getPathToThemeDir()
	{
		return $this->themeManager->getApplicationCurrentTheme()->getPathToThemeDir();
	}

	public function getError($templateName = null)
	{

		$errorCode = null;
		$filePath = null;
		$isEditable = null;
		if ($this->themeManager->isApplicationCurrentThemeReadOnly())
		{
			$errorCode = "THEME_IS_READ_ONLY";
			$isEditable = false;
		}
		elseif (isset($templateName) && $this->doesTemplateFileExist($templateName))
		{
			if (!$this->isTemplateFileWritable($templateName))
			{
				$errorCode = "TEMPLATE_IS_NOT_WRITABLE";
				$filePath = $this->getPathToModuleTemplate($templateName);
                $isEditable = false;
			}
		}
		elseif ($this->doesModuleTemplatesDirExist())
		{
			if (!$this->isModuleTemplatesDirWritable())
			{
				$errorCode = "MODULE_TEMPLATES_DIR_IS_NOT_WRITABLE";
				$filePath = $this->getPathToModuleTemplatesDir();
                $isEditable = false;
			}
		}
		elseif (!$this->isThemeDirWritable())
		{
			$errorCode = "THEME_DIR_IS_NOT_WRITABLE";
			$filePath = $this->getPathToThemeDir();
            $isEditable = false;
		}
        if (!empty($this->error))
        {
            $errorCode = $this->error['errorCode'];
            $filePath = $this->error['filePath'];
            $isEditable = true;
        }

		return array("code" => $errorCode, 'filePath' => $filePath, 'isEditable'=>$isEditable);
	}

	public function createModuleThemeDir()
	{
		$pathToModuleThemeDir = $this->getPathToModuleTemplatesDir();
		mkdir($pathToModuleThemeDir);
		chmod($pathToModuleThemeDir, 0777);
	}

	public function saveTemplate($templateName, $templateContent)
	{
		if (!$this->doesModuleTemplatesDirExist())
		{
			$this->createModuleThemeDir();
		}
		$templateFileExists = $this->doesTemplateFileExist($templateName);
		if (!$templateFileExists)  $this->createTemplateDirectory($templateName);
		$templatePath = $this->getPathToModuleTemplate($templateName);
		\App()->FileSystem->putContentsToFile($templatePath, $templateContent);
		if (!$templateFileExists) chmod($templatePath, 0777);
	}

    public function createTemplate($templateName, $templateContent)
    {

        if ($this->doesTemplateFileExistIncludingParentThemes($templateName))
        {
            $this->error ['errorCode'] = "THEME_ALREADY_EXISTS";
            $this->error ['filePath'] = $this->getPathToModuleTemplate($templateName);
        }
        else
        {
            $this->saveTemplate($templateName, $templateContent);
        }
    }

	private function createTemplateDirectory($templateName)
	{
		$subdirectoryName = pathinfo($templateName, PATHINFO_DIRNAME);
		$templateDir = $this->getPathToModuleTemplatesDir() . "/" . $subdirectoryName;
		if (!is_dir($templateDir)) \App()->FileSystem->getWritableDir($templateDir);
	}

	public function deleteTemplate($templateName)
	{
		\App()->FileSystem->deleteFile($this->getPathToModuleTemplate($templateName));
	}

	public function getTemplatesList()
	{
		$themesTree = $this->themeManager->getThemesTree();
		$currentTheme = $themesTree->getItem($this->themeManager->getCurrentThemeName());

		$templatesListCollector = new \modules\template_manager\lib\TemplatesListCollector();
		$templatesListCollector->setThemeManager($this->themeManager);
		$templatesListCollector->setModuleName($this->moduleName);
		$templatesListCollector->setCurrentTheme($currentTheme);

		$treeWalker = \App()->ObjectMother->createTreeWalker();
		$treeWalker->setHandler($templatesListCollector);
		$treeWalker->walkUp($currentTheme);

		return $templatesListCollector->getTemplateList();
	}

	public function getTemplateContent($templateName)
	{
		$currentTheme = $this->themeManager->getApplicationCurrentTheme();
		$template = $currentTheme->getTemplate($this->moduleName, $templateName);
		return $template->getContentWithoutComments();
	}

    private function doesTemplateFileExistIncludingParentThemes($templateName)
    {
        $currentTheme = $this->themeManager->getApplicationCurrentTheme();
        try
        {
            $currentTheme->getTemplate($this->moduleName, $templateName);
        }
        catch (\modules\smarty_based_template_processor\lib\TemplateNotFoundException $e)
        {
            return false;
        }
        return true;
    }
}

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

class ThemeManager
{
	private $appId;
	private $themesData = array();

	public function __construct($appId)
	{
		$this->appId = $appId;

		$this->themesData[\App()->SystemSettings->getSettingForApp($appId, 'DefaultTheme')] = array
		(
			'moduleName' => null,
			'inherits' => null,
			'readonly' => true,
			'className' => "\\apps\\{$this->appId}\\Theme"
		);

		$themeModules = new \core\ExtensionPoint("apps\\$appId\\IThemeModule");
		foreach ($themeModules as $themeModule)
		{
			$pathToThemeConfigFile = PATH_TO_ROOT . \App()->SystemSettings['ModulesDir'] . $themeModule->getName() . "/config.ini";
			$configData = parse_ini_file($pathToThemeConfigFile);
			$this->themesData[$configData['name']] = array_merge
			(
				array
				(
					'moduleName' => $themeModule->getName(),
					'inherits' => \App()->SystemSettings->getSettingForApp($appId, 'DefaultTheme'),
					'readonly' => false,
					'className' => '\apps\Theme',
				),
				$configData
			);
		}
	}

	public function getThemesTree()
	{
		$this->tree = \App()->ObjectMother->createTreeData();


		foreach ($this->themesData as $themeName => $themeData)
		{
			$treeItem = \App()->ObjectMother->createTreeItem($themeName, $themeData['inherits']);
			$treeItem->addScalarExtraParameter("read_only", $themeData['readonly']);
			$treeItem->addScalarExtraParameter("is_current", $themeName == $this->getCurrentThemeName());
			$this->tree->addItem($treeItem);
		}
		$this->tree->setRelations();
		return $this->tree;
	}

	/**
	 * @param $themeName
	 * @param $baseThemeName
	 * @throws Exception
	 * @return \apps\Theme
	 */
	public function addTheme($themeName, $baseThemeName)
	{
		if (empty($baseThemeName)) $baseThemeName = \App()->SystemSettings->getSettingForApp($this->appId, 'DefaultTheme');

		$themeName = trim($themeName);
		if (empty($themeName))
		{
			$exception = new \modules\template_manager\lib\Exception('EMPTY_VALUE');
			$exception->setData(array('fieldCaption' => 'New Theme Name'));
			throw $exception;
		}
		elseif (!preg_match('/(^\w+$)/', $themeName))
		{
			$exception = new \modules\template_manager\lib\Exception('NOT_VALID_ID_VALUE');
			$exception->setData(array('fieldCaption' => 'New Theme Name'));
			throw $exception;
		}
		elseif ($this->doesThemeExist($themeName))
		{
			throw new \modules\template_manager\lib\Exception('THEME_ALREADY_EXISTS');
		}

		try
		{
			$theme = new \apps\Theme();
			$theme->setName($themeName);
			$theme->setModuleName("custom_theme_{$themeName}");
			$theme->setParentTheme($this->createTheme($baseThemeName));

			if (\App()->FileSystem->fileExists($theme->getPathToThemeDir()))
			{
				throw new \Exception("THEME_ALREADY_EXISTS");
			}

			\App()->FileSystem->getWritableDir($theme->getPathToThemeDir());
			\App()->FileSystem->createFile($theme->getPathToThemeDir() . "Module.php");
			\App()->FileSystem->putContentsToFile($theme->getPathToThemeDir() . "Module.php", $this->getThemeModuleClassFileContent($theme));

			\App()->FileSystem->createFile($theme->getPathToThemeDir() . "config.ini");
			\App()->FileSystem->putContentsToFile($theme->getPathToThemeDir() . "config.ini", $this->getThemeConfigFileContent($theme));

			\App()->FileSystem->getWritableDir($theme->getPathToFilesDir(\App()->SystemSettings['PageTemplatesModuleName']));
			\App()->FileSystem->createFile($theme->getFilePath(\App()->SystemSettings['PageTemplatesModuleName'], "design.css"));

			$this->copyTemplatesToThemeFromItsParents($theme);

			\App()->registerDirsToImplementations(array($theme->getPathToThemeDir()));
			\App()->requestSetupEnvironmentSkippingRebuildInterfacesCache();
			return $theme;
		}
		catch (\Exception $e)
		{
			\App()->ErrorMessages->addMessage($e->getMessage());
			throw new Exception();
		}
	}

	private function getThemeConfigFileContent($theme)
	{
		$content = "name={$theme->getName()}\n";
		if ($theme->getParentTheme()->getName() != \App()->SystemSettings->getSettingForApp($this->appId, 'DefaultTheme'))
			$content .= "inherits={$theme->getParentTheme()->getName()}\n";
		return $content;
	}

	private function getThemeModuleClassFileContent($theme)
	{
		$params = array
		(
			'appId' => $this->appId,
			'newThemeModuleName' => $theme->getModuleName(),
			'newThemeModuleVersion' => \App()->SettingsFromDB->getSettingByName('product_version') . '-1',
			'newThemeModuleCaption' => $theme->getName(),
			'parentThemeModuleName' => $theme->getParentTheme()->getModuleName(),
		);
		$contentForTheme = <<<'CONTENT'
<?php

namespace modules\%newThemeModuleName%;
{
	class Module extends \apps\%appId%\ThemeModule implements \apps\%appId%\ICustomTheme
	{
		protected $name = '%newThemeModuleName%';
		protected $caption = '%newThemeModuleCaption%';
		protected $version = '%newThemeModuleVersion%';
		protected $dependencies = array('%parentThemeModuleName%');
	}
}
CONTENT;

		$contentForBaseProductTheme = <<<'CONTENT'
<?php

namespace modules\%newThemeModuleName%;
{
	class Module extends \apps\%appId%\ThemeModule implements \apps\%appId%\ICustomTheme
	{
		protected $name = '%newThemeModuleName%';
		protected $caption = '%newThemeModuleCaption%';
		protected $version = '%newThemeModuleVersion%';
	}
}
CONTENT;

		$placeholders = array_map(function($paramName) {return '%' . $paramName . '%';}, array_keys($params));
		return str_replace($placeholders, array_values($params), $theme->getParentTheme()->getName() == \App()->SystemSettings->getSettingForApp($this->appId, 'DefaultTheme')? $contentForBaseProductTheme : $contentForTheme);
	}


	public function makeThemeCurrent($themeName)
	{
		if (!$this->doesThemeExist($themeName)) throw new Exception("THEME_NOT_FOUND");
		\App()->SettingsFromDB->updateSetting($this->appId . '_currentTheme', $themeName);
	}
	public function deleteTheme($themeName)
	{
		if ($themeName == $this->getApplicationCurrentTheme($this->appId))
		{
			throw new Exception("THEME_IS_CURRENT");
		}
		$theme = $this->createTheme($themeName);
		if (!\App()->FileSystem->isDeletable($theme->getPathToThemeDir()))
		{
			$exception = new Exception("CANNOT_DELETE_AS_THEME_CONTAINS_NOT_WRITABLE_FILES");
			$exception->setData(array('files' => \App()->FileSystem->getNotWritableFiles($theme->getPathToThemeDir())));
			throw $exception;
		}

		\App()->unregisterDirsFromImplementations(array($theme->getPathToThemeDir()));
		\App()->FileSystem->removeRecursively($theme->getPathToThemeDir());
		\App()->requestSetupEnvironmentSkippingRebuildInterfacesCache();
	}

	public function doesThemeExist($themeName)
	{
		return array_key_exists($themeName, $this->themesData);
	}

	public function getCurrentThemeName()
	{
		$themeNameActions = new \core\ExtensionPoint('modules\template_manager\lib\IDefineThemeName');
		foreach ($themeNameActions as $action)
		{
			$themeName = $action->define();
		}
		return empty($themeName) ? \App()->SettingsFromDB->getSettingByName($this->appId . '_currentTheme') : $themeName;
	}

	public function getThemesList()
	{
		return array_keys($this->themesData);
	}

	public function getCurrentSessionTheme()
	{
		return $this->createTheme($this->getCurrentSessionThemeName());
	}

	public function getThemeInheritanceBranch()
	{
		return new ThemeInheritanceBranch($this->getCurrentSessionTheme());
	}

	private function getCurrentSessionThemeName()
	{
		$themeName = isset($_REQUEST['theme']) ? $_REQUEST['theme'] : \App()->I18N->getCurrentLanguageThemeForApp($this->appId);

		if (!$this->doesThemeExist($themeName))
		{
			$themeName = \App()->Session->getValue('CURRENT_THEME');
			if (!$this->doesThemeExist($themeName))
			{
				$themeName = $this->getCurrentThemeName();
				if (!$this->doesThemeExist($themeName))
				{
					$themeName = \App()->SystemSettings['DefaultTheme'];
				}
			}
		}
		\App()->Session->setValue('CURRENT_THEME', $themeName);
		return $themeName;
	}

	public function isApplicationCurrentThemeReadOnly()
	{
		return $this->getApplicationCurrentTheme()->isReadOnly();
	}

	public function getApplicationCurrentTheme()
	{
		return $this->createTheme($this->getCurrentThemeName());
	}

	public function createTheme($themeName)
	{
		$themeClassName = $this->themesData[$themeName]['className'];
		/**
		 * @var \apps\AbstractTheme $theme
		 */
		$theme = new $themeClassName;
		$theme->setName($themeName);
		$theme->setModuleName($this->themesData[$themeName]['moduleName']);
		$theme->setReadOnly($this->themesData[$themeName]['readonly']);
		$theme->setParentThemeName($this->themesData[$themeName]['inherits']);
		$theme->setFileNotFoundAction(\App()->ObjectMother->createFileNotFoundAction());

		if (!is_null($this->themesData[$themeName]['inherits']))
		{
			$theme->setParentTheme($this->createTheme($this->themesData[$themeName]['inherits']));
		}
		return $theme;
	}

    /**
     * @return array
     */
    public function getThemesData()
    {
        return $this->themesData;
    }

	/**
	 * @param $theme \apps\Theme
	 */
	private function copyTemplatesToThemeFromItsParents($theme)
	{
		$moduleTemplateProviders = \App()->TemplateProvidersManager->getModuleTemplateProviders($this->appId);

		$branch = new ThemeInheritanceBranch($theme->getParentTheme());
		foreach ($branch as $t)
		{
			foreach ($moduleTemplateProviders as $provider)
			{
				$src = $t->getPathToModuleTemplatesDir($provider->getModuleName());
				$dest = $theme->getPathToThemeDir() . $provider->getModuleName();
				\App()->FileSystem->copyDirContents($src, $dest, true);
			}
		}
	}
}

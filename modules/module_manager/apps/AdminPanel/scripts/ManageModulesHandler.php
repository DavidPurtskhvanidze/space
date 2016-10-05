<?php
/**
 *
 *    Module: module_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: module_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19801, 2016-06-17 13:20:13
 *
 *    This file is part of the 'module_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\module_manager\apps\AdminPanel\scripts;
 
class ManageModulesHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\miscellaneous\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'module_manager';
	protected $functionName = 'manage_modules';

	public function respond()
	{
		$modulesInfoUpdater = new \modules\module_manager\lib\ModulesInfoUpdater();
		$modulesInfoUpdaterWithCache = new \modules\module_manager\lib\ModulesInfoUpdaterWithCache($modulesInfoUpdater);
		$modulesInfoUpdaterWithCache->update();

		$modules = \App()->Request['modules'];
		$action = \App()->Request['action'];

		if (!is_null($action))
		{
			if (!empty($modules))
			{
				try
				{
					if ($action == 'enable')
					{
						if (\App()->ModuleManager->enableModules($modules))
						{
							throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, $this->functionName) . '?action=success_enable&' . http_build_query(array('modules' => $modules)));
						}
					}
					elseif ($action == 'success_enable')
					{
						\App()->trigger('modules\module_manager\apps\AdminPanel\IAfterModulesEnable', $modules);
					}
					elseif ($action == 'disable')
					{
						\App()->ModuleManager->disableModules($modules);
					}
					elseif ($action == 'install')
					{
						if (\App()->ModuleManager->installModules($modules))
						{
							throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, $this->functionName) . '?action=success_install&' . http_build_query(array('modules' => $modules)));
						}
					}
					elseif ($action == 'success_install')
					{
						\App()->trigger('modules\module_manager\apps\AdminPanel\IAfterModulesInstall', $modules);
					}
					elseif ($action == 'upgrade')
					{
						if (\App()->ModuleManager->upgradeModules($modules))
						{
							throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, $this->functionName) . '?action=success_upgrade&' . http_build_query(array('modules' => $modules)));
						}
					}
					elseif ($action == 'success_upgrade')
					{
						\App()->trigger('modules\module_manager\apps\AdminPanel\IAfterModulesUpgrade', $modules);
					}
				}
				catch (\modules\module_manager\lib\Exception $e)
				{
				}
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, $this->functionName) . '?restore&' . http_build_query(array('modules' => $modules)));
			}
			elseif($action == 'installAll')
			{
				try
				{
					\App()->ModuleManager->installAllModules();
				}
				catch (\modules\module_manager\lib\Exception $e)
				{
				}
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, $this->functionName));
			}
			elseif($action == 'upgradeAll')
			{
				try
				{
					\App()->ModuleManager->upgradeAllModules();
				}
				catch (\modules\module_manager\lib\Exception $e)
				{
				}
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, $this->functionName));
			}
			if ($action == 'refresh_module_list')
			{
				$this->refreshModuleList();
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, $this->functionName) . '?restore');
			}
		}
		$templateProcessor = \App()->getTemplateProcessor();
		$allModulesInfo = \App()->ModuleManager->getAllModulesInfo();
		usort($allModulesInfo, function ($a, $b) {return strcasecmp($a['caption'], $b['caption']);});

		$systemModulesList = \App()->ModuleManager->getSystemModulesList();
		$availableModulesInfo = array_filter($allModulesInfo, function ($module) use ($systemModulesList) {return $module['type'] == 'REGULAR' && !in_array($module['name'], $systemModulesList);});
		$sampleDataModulesInfo = array_filter($allModulesInfo, function ($module) {return $module['type'] == 'SAMPLE_DATA';});
		$systemModulesInfo = array_filter($allModulesInfo, function ($module) use ($systemModulesList) {return in_array($module['name'], $systemModulesList);});

		if (!is_null($modules))
		{
			array_walk($availableModulesInfo, function (&$module) use ($modules) {if (in_array($module['name'], $modules)) $module['checked'] = true;});
			array_walk($sampleDataModulesInfo, function (&$module) use ($modules) {if (in_array($module['name'], $modules)) $module['checked'] = true;});
		}

		$templateProcessor->assign('availableModules', $availableModulesInfo);
		$templateProcessor->assign('sampleDataModules', $sampleDataModulesInfo);
		$templateProcessor->assign('systemModules', $systemModulesInfo);
		$templateProcessor->assign('moduleUpdates', \App()->ModuleManager->getModuleUpdatesInfo());
		$templateProcessor->assign('addonModules', \App()->ModuleManager->getAddonModulesInfo());
		$templateProcessor->display("manage_modules.tpl");
	}

	private function refreshModuleList()
	{
		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\module_manager\apps\AdminPanel\IRefreshModuleListValidator');
		foreach ($validators as $validator)
		{
			$canPerform &= $validator->isValid();
		}
		if ($canPerform)
		{
			\App()->CacheManager->clearCache('modulesInfo');
			\App()->requestSetupEnvironment();
		}
	}

	public function getCaption()
	{
		return "Module Manager";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array(\App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName));
	}

	public static function getOrder()
	{
		return 600;
	}
}

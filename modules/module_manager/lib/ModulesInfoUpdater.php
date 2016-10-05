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


namespace modules\module_manager\lib;

class ModulesInfoUpdater implements IModulesInfoUpdater
{
	public function update()
	{
		$version = \App()->SettingsFromDB->getSettingByName('product_version');
		$productName = \App()->SettingsFromDB->getSettingByName('product_name');
		$this->processXml(\App()->SystemSettings['ModulesDataUrl'] . $version . '/common.xml');
		$this->processXml(\App()->SystemSettings['ModulesDataUrl'] . $version . '/' . $productName . '.xml');
	}

	private function processXml($url)
	{
		$xml = @simplexml_load_file($url);

		if (empty($xml->module))
		{
			\App()->WarningMessages->addMessage('CANNOT_FETCH_MODULES_INFO', array('url' => $url));
			return;
		}

		foreach ($xml->module as $module)
		{
			$moduleName = (string)$module['name'];
			$moduleCaption = (string)$module['caption'];
			foreach ($module->version as $version)
			{
				$moduleVersion = (string)$version['value'];
				$moduleUrl = (string)$version['url'];
				if ($version->masterModules->module->count() == 0)
				{
					$this->addModuleToDependencyTable($moduleName, $moduleCaption, $moduleVersion, $moduleUrl);
				}
				else
				{
					foreach ($version->masterModules->module as $masterModule)
					{
						$this->updateModuleDependency($moduleName, $moduleCaption, $moduleVersion, $moduleUrl, (string)$masterModule['name'], (string)$masterModule['minVersion'], (string)$masterModule['maxVersion']);
					}
				}
			}
		}
	}

	private function addModuleToDependencyTable($name, $caption, $version, $url)
	{
		$result = \App()->DB->query("SELECT `module` FROM `module_manager_module_dependencies` WHERE `module` = ?s AND `version` = ?s", $name, $version);
		if (empty($result))
		{
			\App()->DB->query("INSERT INTO `module_manager_module_dependencies` (`module`, `caption`, `version`, `url`, `master`, `min_version`, `max_version`) VALUES (?s, ?s, ?s, ?s, ?s, ?s, ?s)",
				$name, $caption, $version, $url, null, null, null);
		}
	}

	private function updateModuleDependency($name, $caption,  $version, $url, $master, $minVersion, $maxVersion)
	{
        \App()->DB->query("INSERT INTO `module_manager_module_dependencies` (`module`, `caption`, `version`, `url`, `master`, `min_version`, `max_version`)
                              VALUES (?s, ?s, ?s, ?s, ?s, ?s, ?s)
                              ON DUPLICATE KEY UPDATE  `min_version` = ?s, `max_version` = ?s, `url` = ?s, `caption` = ?s",
            $name, $caption, $version, $url, $master, $minVersion, $maxVersion,
            $minVersion, $maxVersion, $url, $caption);
	}
}

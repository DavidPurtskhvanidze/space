<?php
/**
 *
 *    Module: admin_dashboard v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: admin_dashboard-7.3.0-1
 *    Tag: tags/7.3.0-1@18504, 2015-08-24 13:35:28
 *
 *    This file is part of the 'admin_dashboard' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\admin_dashboard\apps\AdminPanel;

class AdminDashboardManager implements \core\IService
{
	public function getDashboardItems()
	{
		$freshStatsProviders = $this->getFreshStatsProviders();
		$statBlocks = $this->getStatBlocks();

		$moduleName = "admin_dashboard";
		$subfunctions = array();

		/**
		 * @var IFreshStatsProvider $provider
		 */
		foreach ($freshStatsProviders as $provider)
		{
			$providerClassname = get_class($provider);
			$permissionId = "{$moduleName}:{$providerClassname}";
			$subfunctions[$permissionId] = array
			(
				'value' => $permissionId,
				'functionName' => "Fresh Stats: " . $provider->getCaption(),
				'moduleName' => $moduleName,
			);
		}

		/**
		 * @var IStatBlock $block
		 */
		foreach ($statBlocks as $block)
		{
			$providerClassname = get_class($block);
			$permissionId = "{$moduleName}:{$providerClassname}";
			$subfunctions[$permissionId] = array
			(
				'value' => $permissionId,
				'functionName' => "Stats Block: " . $block->getCaption(),
				'moduleName' => $moduleName,
			);
		}
		return $subfunctions;
	}

	public function getStatBlocks()
	{
		$statBlocks = new \core\ExtensionPoint('modules\admin_dashboard\apps\AdminPanel\IStatBlock');
		return $statBlocks;
	}

	public function getFreshStatsProviders()
	{
		$freshStatsProviders = new \core\ExtensionPoint('modules\admin_dashboard\apps\AdminPanel\IFreshStatsProvider');
		return $freshStatsProviders;
	}

	public function getFreshStatsProvidersGranted()
	{
		return new AdminDashboardAccessGrantedItemsIterator($this->getFreshStatsProviders());
	}

	public function getStatBlocksGranted()
	{
		return new AdminDashboardAccessGrantedItemsIterator($this->getStatBlocks());
	}
}

<?php
/**
 *
 *    Module: export_ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19778, 2016-06-17 13:19:13
 *
 *    This file is part of the 'export_ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_ip_blocklist\apps\AdminPanel\scripts;

class ExportIpBlocklistHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'export_ip_blocklist';
	protected $functionName = 'export_blocklist';

	private $_ipRangeManager;

	public function respond()
	{
		$this->_ipRangeManager = \App()->IpRangeManager;

		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'export')
		{
			$properties = $_REQUEST['export_properties'];
			$allIpRangeSIDs = $this->_ipRangeManager->getAllIpRangeSIDs();

			\App()->Session->getContainer('EXPORT_IP_BLOCKLIST')->setValue('sids', $allIpRangeSIDs);
			\App()->Session->getContainer('EXPORT_IP_BLOCKLIST')->setValue('properties', $properties);
			throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'export_blocklist_file'));
		}
		else
		{
			$templateProcessor = \App()->getTemplateProcessor();
			$properties = $this->_ipRangeManager->getIpRangeProperties();
			$allIpRangeSIDs = $this->_ipRangeManager->getAllIpRangeSIDs();
			if (empty($allIpRangeSIDs))
			{
				$templateProcessor->assign('emptyIpRangeList', true);
			}
			$templateProcessor->assign('properties', $properties);
			$templateProcessor->display('export_ip_blocklist.tpl');
		}
	}
}

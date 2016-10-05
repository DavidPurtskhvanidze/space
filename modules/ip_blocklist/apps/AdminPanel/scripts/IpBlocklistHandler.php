<?php
/**
 *
 *    Module: ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19789, 2016-06-17 13:19:41
 *
 *    This file is part of the 'ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\ip_blocklist\apps\AdminPanel\scripts;

class IpBlocklistHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\miscellaneous\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'ip_blocklist';
	protected $functionName = 'blocklist';

    /**
     * IpRangeManager
     * @var IpRangeManager
     */
    private $_ipRangeManager = null;
	public function respond()
	{
		$this->_ipRangeManager = \App()->IpRangeManager;
		$this->mapActionToMethod
        (
			array
			(
				'DELETE' => array($this, '_actionDeleteIpRanges'),
			)
		);
		$this->_actionListIpRanges();
	}
    /**
     * Displays index page of ip blocklist manager
     */
    private function _actionListIpRanges()
    {
        $templateProcessor = \App()->getTemplateProcessor();

        $navgatorFilterData = array();
        $navgatorOrderData = array();
        $navgatorPagerData = array();

        $ipBlockListStructure = $this->_ipRangeManager->createTemplateStructureForIpRanges($_REQUEST, $navgatorFilterData, $navgatorOrderData, $navgatorPagerData);

        $templateProcessor->assign('block_list', $ipBlockListStructure);
        $templateProcessor->assign('navgator_filters', $navgatorFilterData);
        $templateProcessor->assign('navgator_order', $navgatorOrderData);
        $templateProcessor->assign('navgator_pager', $navgatorPagerData);

        $templateProcessor->display('block_list.tpl');
    }
    /**
     * Deletes from database Ip Ranges by $ipRangeSIDs
     * @param array $ipRangeSIDs
     */
    private function _actionDeleteIpRanges($ipRangeSIDs)
    {
        if (!is_array($ipRangeSIDs))
            return;

        foreach ($ipRangeSIDs as $sid => $dummy)
        {
            $this->_ipRangeManager->deleteIpRangeBySID($sid);
        }
    }
    /**
     * Action to method mapper
     * @param IpBlocklistHandler $obj
     * @param array $map
     */
    private function mapActionToMethod($map)
    {
		if (!isset($_REQUEST['action'], $_REQUEST['ip_ranges']))
			return;
	    $action = strtoupper($_REQUEST['action']);
        $ipRangeSIDs = $_REQUEST['ip_ranges'];
        if (isset($map[$action]))
        {
	        call_user_func($map[$action], $ipRangeSIDs);
        }
    }

	public function getCaption()
	{
		return "IP Blocklist Manager";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array
		(
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'add_ip_range'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'edit_ip_range'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'import_blocklist'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'export_blocklist'),
		);
	}

	public static function getOrder()
	{
		return 500;
	}
}

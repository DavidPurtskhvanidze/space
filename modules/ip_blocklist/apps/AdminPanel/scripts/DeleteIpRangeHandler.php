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

class DeleteIpRangeHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'ip_blocklist';
	protected $functionName = 'delete_ip_range';

    /**
     * IpRangeManager
     * @var IpRangeManager
     */
    private $_ipRangeManager = null;
	public function respond()
	{
        $this->_ipRangeManager = \App()->IpRangeManager;

        $templateProcessor = \App()->getTemplateProcessor();
        $ipRangeSID    = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;
        $ipRange       = $this->_ipRangeManager->getIpRangeBySID($ipRangeSID);

        if (!is_null($ipRange))
        {
            $this->_ipRangeManager->deleteIpRangeBySID($ipRangeSID);
			\App()->SuccessMessages->addMessage('BLOCKLIST_IP_DELETED', array('ip' => $ipRange->getPropertyValue('ip_range')), 'ip_blocklist');
            throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'blocklist'));
        } else {
            echo 'The system  cannot proceed as Ip Range SID is not set or does not exist';
        }
	}
}
?>

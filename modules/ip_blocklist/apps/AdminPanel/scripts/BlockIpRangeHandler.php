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

class BlockIpRangeHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'ip_blocklist';
	protected $functionName = 'block_ip_range';
	protected $rawOutput = true;

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$ipRange = \App()->IpRangeManager->createIpRange($_REQUEST);
		$ipRangeForm = new \lib\Forms\Form($ipRange);

		$actionDone = false;
		if ((\App()->Request['action'] == 'save_info') && $ipRangeForm->isDataValid())
		{
			\App()->IpRangeManager->saveIpRange($ipRange);
			\App()->SuccessMessages->addMessage('BLOCKLIST_IP_ADDED', array('ip' => $ipRange->getPropertyValue('ip_range')), 'ip_blocklist');
			$actionDone = true;
		}

		$ipRangeForm->registerTags($templateProcessor);
		$templateProcessor->assign("form_fields", $ipRangeForm->getFormFieldsInfo());
		$templateProcessor->assign("actionDone", $actionDone);
		$templateProcessor->assign("returnBackUri", \App()->Request['returnBackUri']);
		$templateProcessor->assign("added_ip_range", $ipRange->getPropertyValue('ip_range'));
		$templateProcessor->display("block_ip_range.tpl");
	}
}

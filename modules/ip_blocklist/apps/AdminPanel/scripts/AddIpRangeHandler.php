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

class AddIpRangeHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'ip_blocklist';
	protected $functionName = 'add_ip_range';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$ipRange = \App()->IpRangeManager->createIpRange($_REQUEST);
		$ipRangeForm = new \lib\Forms\Form($ipRange);

		$formIsSubmitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save_info');
		if ($formIsSubmitted && $ipRangeForm->isDataValid())
		{
			\App()->IpRangeManager->saveIpRange($ipRange);
			\App()->SuccessMessages->addMessage('BLOCKLIST_IP_ADDED', array('ip' => $ipRange->getPropertyValue('ip_range')), 'ip_blocklist');
			throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'blocklist'));
		}

		$templateProcessor->assign("form_fields", $ipRangeForm->getFormFieldsInfo());
		$ipRangeForm->registerTags($templateProcessor);
		$templateProcessor->display("add_ip_range.tpl");
	}
}

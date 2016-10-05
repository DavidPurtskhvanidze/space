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

class EditIpRangeHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'ip_blocklist';
	protected $functionName = 'edit_ip_range';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$ipRangeSID = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;
		$ipRange = \App()->IpRangeManager->getIpRangeBySID($ipRangeSID);

		$form = new \lib\Forms\Form();

		if (!is_null($ipRange))
		{
			$ipRangeInfo = array_merge($ipRange->toArray(), $_REQUEST);
			$ipRange = \App()->IpRangeManager->createIpRange($ipRangeInfo);
			$ipRange->setSID($ipRangeSID);

			$form = new \lib\Forms\Form($ipRange);

			$formIsSubmitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save_info');
			if ($formIsSubmitted && $form->isDataValid())
			{
				\App()->IpRangeManager->saveIpRange($ipRange);
				\App()->SuccessMessages->addMessage('BLOCKLIST_IP_EDITED', array(), 'ip_blocklist');
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'blocklist'));
			}
		}
		else
		{
			\App()->ErrorMessages->addMessage('IP_RANGE_SID_NOT_SET');
			$templateProcessor->assign("do_not_render_form", true);
		}

		$templateProcessor->assign("object_sid", $ipRangeSID);
		$templateProcessor->assign("form_fields", $form->getFormFieldsInfo());
		$form->registerTags($templateProcessor);
		$templateProcessor->display("edit_ip_range.tpl");
	}
}

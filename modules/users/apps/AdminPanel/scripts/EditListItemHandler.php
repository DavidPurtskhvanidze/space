<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\apps\AdminPanel\scripts;

class EditListItemHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'edit_list_item';
	protected $rawOutput = true;

	public function respond()
	{
		$error = null;
		$UserProfileFieldListItemManager = new \modules\users\lib\UserProfileField\UserProfileFieldListItemManager();
		if (!isset($_REQUEST['field_sid'], $_REQUEST['item_sid']))
		{
			$error = "PARAMS_MISSING";
		}
		else if (is_null($list_item = $UserProfileFieldListItemManager->getListItemBySID($_REQUEST['item_sid'])))
		{
			$error = "WRONG_PARAMS";
		}
		else if (empty($_REQUEST['list_item_value']))
		{
			$error = "EMPTY_VALUE";
		}
		else
		{
			$_REQUEST['list_item_value'] = trim($_REQUEST['list_item_value']);
			$list_item->setValue($_REQUEST['list_item_value']);
			if (!$UserProfileFieldListItemManager->saveListItem($list_item))
				$error = "VALUE_ALREADY_EXISTS";
		}

		if ($error)
		{
			header("HTTP/1.1 406 Not Acceptable");
			$templateProcessor = \App()->getTemplateProcessor();
			$templateProcessor->assign("errors", array('Value' => $error));
			$templateProcessor->display("field_errors.tpl");
		}
	}
}
?>

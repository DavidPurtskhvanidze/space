<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\AdminPanel\scripts;

class EditListItemHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'edit_list_item';
	protected $rawOutput = true;

	public function respond()
	{
		$error = null;
		$ListingFieldListItemManager = new \modules\classifieds\lib\ListingField\ListingFieldListItemManager();
		if (!isset($_REQUEST['field_sid'], $_REQUEST['item_sid']))
		{
			$error = "PARAMS_MISSING";
		}
		else if (is_null($list_item = $ListingFieldListItemManager->getListItemBySID($_REQUEST['item_sid'])))
		{
			$error = "WRONG_PARAMS";
		}
		else if (empty($_REQUEST['list_item_value']) && !is_numeric($_REQUEST['list_item_value']))
		{
			$error = "EMPTY_VALUE";
		}
		else
		{
			$_REQUEST['list_item_value'] = trim($_REQUEST['list_item_value']);
			$list_item->setValue($_REQUEST['list_item_value']);
			if (!$ListingFieldListItemManager->saveListItem($list_item))
				$error = "VALUE_ALREADY_EXISTS";
			else
				\App()->ListingManager->onChangeListItem($list_item);
		}
		
		if ($error)
		{
			header("HTTP/1.1 406 Not Acceptable");
			$templateProcessor = \App()->getTemplateProcessor();
			$templateProcessor->assign("errors", array('Value' => $error));
			$templateProcessor->display("miscellaneous^field_errors.tpl");
		}
	}
}
?>

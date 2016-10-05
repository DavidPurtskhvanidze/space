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

// version 5 wrapper header

class RelocateListingCategoryHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'relocate_listing_category';
	protected $rawOutput = true;

	public function respond()
	{
		
// end of version 5 wrapper header



$errors = array();
$template_processor = \App()->getTemplateProcessor();

$relocating_category_sid = isset($_REQUEST['relocating_category_sid']) ? $_REQUEST['relocating_category_sid'] : null;
$dest_category_sid = isset($_REQUEST['dest_category_sid']) ? $_REQUEST['dest_category_sid'] : null;
$action_name = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

$categoryManager = \App()->ObjectMother->createCategoryManager();

if (!empty($action_name))
{
	$categoryActionFactory = \App()->ObjectMother->createCategoryActionFactory();
	$action = $categoryActionFactory->createAction($action_name, $_REQUEST);
	if ($action->canPerform())
	{
		$action->perform();
		$template_processor->assign('action_done', true);
		$template_processor->assign('action_name', $action_name);
		$template_processor->assign('move_dest_category_sid', $dest_category_sid);
		if ($action_name == 'move')
			\App()->SuccessMessages->addMessage('CATEGORY_MOVED');
		elseif ($action_name == 'set_child_of')
			\App()->SuccessMessages->addMessage('CATEGORY_MOVED_IN', array('destination_category' => $categoryManager->getInfoBySID($dest_category_sid)));
	}
	else
	{
		$errors = $action->getErrors();
	}
}

$parent_sid = $categoryManager->getParentSID($relocating_category_sid);
$subcategories_of_current_listing_category = $categoryManager->getChildren($parent_sid);
$grandparent_category = $categoryManager->getGrandParent($relocating_category_sid);

$relocating_category = $categoryManager->getInfoBySID($relocating_category_sid);
$destination_category = $categoryManager->getInfoBySID($dest_category_sid);

$template_processor->assign('relocating_category', $relocating_category);
$template_processor->assign('destination_category', $destination_category);
$template_processor->assign('grandparent_category', $grandparent_category);
$template_processor->assign('subcategories_of_current_listing_category', $subcategories_of_current_listing_category);
$template_processor->assign('errors', $errors);
$template_processor->display('relocate_listing_category.tpl');

//  version 5 wrapper footer

	}
}
// end of version 5 wrapper footer
?>

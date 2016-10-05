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

class AddListingHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\classifieds\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'add_listing';

	private function getSingleCategory($categoriesInfo)
	{
		foreach($categoriesInfo as $cat) if ($cat['sid'] != 0 ) return $cat;
	}

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();

		$category_id = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : null;
		
		if (is_null($category_id)) {
			
			$categories_info = \App()->CategoryManager->getAllCategoriesInfo();
			
			if (count($categories_info) == 2) {
				
				$category_info = $this->getSingleCategory($categories_info);
				
				$category_id = $category_info['id'];
				
				throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?category_id=$category_id");
				
			} 
		        
		    $node = \App()->CategoryTree->getNode(0);
		    
		    $template_processor->assign("category", $node->toArray());
			
			$template_processor->display("add_listing_choose_category.tpl");
			
		} else {
			
			$category_sid  = \App()->CategoryManager->getCategorySIDByID($category_id);
			$category_info = \App()->CategoryManager->getInfoBySID($category_sid);
			
			$listing = \App()->ObjectMother->getListingFactory()->getListing($_REQUEST, $category_sid);
			
			$propertiesToExclude = array('sid', 'user_sid', 'activation_date', 'type', 'category_sid', 'listing_package', 'expiration_date', 'active', 'id', 'moderation_status', 'views', 'package', 'pictures', 'user', 'username', 'keywords', 'category');
			array_walk($propertiesToExclude, array($listing, 'deleteProperty'));
			$add_listing_form = new \lib\Forms\Form($listing);
			
			$add_listing_form->registerTags($template_processor);
			
			$form_submitted = isset($_REQUEST['action_add']) || isset($_REQUEST['action_add_pictures']);
				
			$template_processor->assign("category_info", $category_info);
			
			if ($form_submitted && $add_listing_form->isDataValid()) {

				\App()->ListingManager->saveListing($listing);
				\App()->ListingManager->setModerationStatus($listing->getSID(), 'APPROVED');
				
				\App()->SuccessMessages->addMessage('LISTING_ADDED', array('listingId' => $listing->getSid()));
				
				$afterAddListingActions = new \core\ExtensionPoint('modules\classifieds\apps\AdminPanel\IAfterAddListingAction');
				foreach ($afterAddListingActions as $afterAddListingAction)
				{
					$afterAddListingAction->setListing($listing);
					$afterAddListingAction->perform();
				}

				if (isset($_REQUEST['action_add_pictures']))
				{
					throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'manage_pictures') . "?listing_id=" . $listing->getSID());
				}
                $template_processor->assign("listingId", $listing->getSID());
				$template_processor->display("add_listing_success.tpl");
		
			} else {
				
				$template_processor->assign("object_sid", $listing->getSID());
				$template_processor->assign("form_fields", $add_listing_form->getFormFieldsInfo());
				$template_processor->assign("category_id", $category_id);
				$template_processor->display("input_form.tpl");
			}
		}
	}

	public static function getOrder()
	{
		return 200;
	}

	public function getCaption()
	{
		return "Add Listing";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('add_listing');
	}

	public function getHighlightUrls()
	{
		return array();
	}
}

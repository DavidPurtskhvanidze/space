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

class DisplayListingHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'display_listing';

	public function respond()
	{

		$template_processor = \App()->getTemplateProcessor();
		
		$listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : null;
		
		if (is_null($listing_id))
		{
			\App()->ErrorMessages->addMessage('LISTING_ID_DOESNOT_SPECIFIED', array('listingId' => $listing_id));
			$template_processor->assign("errors", true);
			$form = new \lib\Forms\Form();
			$form->registerTags($template_processor);
		}
		else
		{
		    $listing = \App()->ListingManager->getObjectBySID($listing_id);
		
		    if (!empty($listing))
		    {
                $messages = isset($_REQUEST['message']) ? array(array('content' => $_REQUEST['message'])) : array();
				$userInfo = \App()->UserManager->getUserInfoBySID($listing->getUserSID());

				$addListingProperties = new \core\ExtensionPoint('modules\classifieds\apps\AdminPanel\IAddListingPropertyOnDisplayListing');
				foreach ($addListingProperties as $addListingProperty)
				{
					$addListingProperty->setListing($listing);
					$addListingProperty->perform();
				}
				
		        $template_processor->assign("messages", $messages);
                $template_processor->assign("userinfo_sid", $userInfo['sid']);
                $template_processor->assign("userinfo_username", $userInfo['username']);
                $template_processor->assign("userinfo_trusted", $userInfo['trusted_user']);
                $template_processor->assign("listing_id", $listing_id);

				$listingDisplayer = new \modules\classifieds\lib\Listing\ListingDisplayer();
				$listingDisplayer->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
                $template_processor->assign("listing", $listingDisplayer->wrapListing($listing));

			    $listing->addPicturesProperty();

				$listingDisplayer = new \modules\classifieds\lib\Listing\ListingDisplayer();
				$listingDisplayer->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);

				$template_processor->assign("package", \App()->ListingPackageManager->createDisplayTemplateStructureForPackageByListingSID($listing_id));
				$template_processor->assign("listing", $listingDisplayer->wrapListing($listing));
				
				$propertiesToExclude = array();
				array_walk($propertiesToExclude, array($listing, 'deleteProperty'));

				$display_form = new \lib\Forms\Form($listing);
				$display_form->registerTags($template_processor);
		
				$form_fields = $display_form->getFormFieldsInfo(); 
				$template_processor->assign("form_fields", $form_fields);

				if (!empty($_REQUEST['searchId']) && !is_null($search = $this->getSearch($_REQUEST['searchId'], $listing)))
				{
                    $search->setObjectSid($listing->getSID());
					$template_processor->assign("listing_search", new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
				}
			}
		    else
			{
				\App()->ErrorMessages->addMessage('LISTING_DOESNOT_EXIST', array('listingId' => $listing_id));
				$template_processor->assign("errors", true);
				$form = new \lib\Forms\Form();
				$form->registerTags($template_processor);
			}
		}
		$template_processor->display("display_listing.tpl");
	}

	private function getSearch($searchId)
	{
				if (is_null(\App()->Session->getContainer('SEARCHES')->getValue($searchId))) return null;
		$search = unserialize(\App()->Session->getContainer('SEARCHES')->getValue($searchId));
		$search->setDB(\App()->DB);
		$searchMetadata = \App()->Session->getContainer('SEARCHES_METADATA')->getValue($searchId);
		$search->setModelObject(\App()->ListingFactory->getListing(array(), $searchMetadata['categorySid']));
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		return $search;
	}
}
?>

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

class DeleteCategoryFieldHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'delete_category_field';

	public function respond()
	{
		$listingFieldSids = \App()->Request['sids'];
		if (!is_null($listingFieldSids)) {

			foreach ($listingFieldSids as $listingFieldSid) {
				$canPerform = true;
				$validators = new \core\ExtensionPoint('modules\classifieds\apps\AdminPanel\IDeleteCategoryFieldValidator');
				foreach ($validators as $validator) {
					$validator->setCategoryFieldSid($listingFieldSid);
					$canPerform &= $validator->isValid();
				}

				$listingFieldInfo = \App()->ListingFieldManager->getInfoBySID($listingFieldSid);
				if ($canPerform) {
					\App()->ListingManager->onDeleteField($listingFieldInfo);
					\App()->ListingFieldManager->deleteListingFieldBySID($listingFieldSid);
				}
			}

			throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'category_fields') . '?sid=' . $listingFieldInfo['category_sid']);
		} else {
			echo 'The system cannot proceed as Category Field SID is not set';
		}
	}
}

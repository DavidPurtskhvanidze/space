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

class EditListingPictureHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'edit_picture';

	public function respond()
	{
		$picture_sid = isset($_REQUEST['picture_sid']) ? $_REQUEST['picture_sid'] : null;
		$listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : null;
		$pictureCaption = isset($_REQUEST['picture_caption']) ? $_REQUEST['picture_caption'] : null;

		if (!is_null($picture_sid) && !is_null($listing_id)) {
			$gallery = \App()->ListingGalleryManager->createListingGallery();
			$picture_info = $gallery->getPictureInfoBySID($picture_sid);
			$picture_info['caption'] = $pictureCaption;
			$gallery->updatePictureCaption($picture_sid, $picture_info['caption']);
		}
	}
}
?>

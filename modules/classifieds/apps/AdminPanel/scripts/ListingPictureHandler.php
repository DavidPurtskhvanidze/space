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

class ListingPictureHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'listing_picture';
	protected $rawOutput = true;

	public function respond()
	{
		
// end of version 5 wrapper header


$picture_sid = isset($_REQUEST['picture_id']) ? $_REQUEST['picture_id'] : null;

$picture_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;

if (!is_null($picture_sid)) {

	$gallery = \App()->ListingGalleryManager->createListingGallery();

	$pictures_info = $gallery->getPictureInfoBySID($picture_sid);

	if (!empty($pictures_info)) {
		
		header('Expires:' . date('D, d M Y 00:00:00', strtotime("+1 year")) . ' GMT');
		header('Cache-Control:');
		header('Pragma:');
		
		if ($picture_type == 'thumb') {
			
			header("Content-Type: image/jpeg");

			echo $pictures_info['thumbnail']; 
		
		} else {

			header("Content-Type: image/jpeg");
			
			echo $pictures_info['picture'];
			
		}
		
	}
	
}


//  version 5 wrapper footer

	}
}
// end of version 5 wrapper footer
?>

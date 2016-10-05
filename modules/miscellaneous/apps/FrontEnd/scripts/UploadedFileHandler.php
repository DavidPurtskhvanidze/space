<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\FrontEnd\scripts;

class UploadedFileHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Uploaded File';
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'uploaded_file';
	protected $rawOutput = true;

	public function respond()
	{
		$picture_id = isset($_REQUEST['file_id']) ? $_REQUEST['file_id'] : null;
		$picture_info = \App()->UploadPictureManager->getUploadedPictureInfo($picture_id);
		if (!is_null($picture_id) && (!is_null($picture_info)))
		{
			if (!empty($picture_info['file_content']))
			{
				header("Content-Type: image/jpeg");
				echo $picture_info['file_content'];
			}
		}
	}
}

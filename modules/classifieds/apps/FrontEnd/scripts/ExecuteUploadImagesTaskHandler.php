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


namespace modules\classifieds\apps\FrontEnd\scripts;

class ExecuteUploadImagesTaskHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Execute Upload Images Task';
	protected $moduleName = 'classifieds';
	protected $functionName = 'execute_upload_images_task';
	protected $rawOutput = true;

	public function respond()
	{
		$uploadImagesTask = new \modules\classifieds\lib\ScheduledTasks\UploadListingImagesTask();
		$uploadImagesTask->setShowLog(!is_null(\App()->Request['showlog']));
		$uploadImagesTask->run();
	}
}

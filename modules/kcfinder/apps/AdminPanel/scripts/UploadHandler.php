<?php
/**
 *
 *    Module: kcfinder v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: kcfinder-7.3.0-1
 *    Tag: tags/7.3.0-1@18531, 2015-08-24 13:36:38
 *
 *    This file is part of the 'kcfinder' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\kcfinder\apps\AdminPanel\scripts;

class UploadHandler extends \modules\kcfinder\apps\AdminPanel\Initializer
{
	protected $moduleName = 'kcfinder';
	protected $functionName = 'upload';
	protected $rawOutput = true;

	function respond()
	{
		$this->initialize();
		$uploader = new \uploader();
		$uploader->upload();
	}
	
}

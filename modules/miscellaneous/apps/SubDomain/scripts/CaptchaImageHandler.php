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


namespace modules\miscellaneous\apps\SubDomain\scripts;

class CaptchaImageHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'captcha_image';
	protected $rawOutput = true;

	public function respond()
	{
		$captcha = \App()->ObjectMother->createCaptcha();
		$captcha->displayImage();
	}
}
?>

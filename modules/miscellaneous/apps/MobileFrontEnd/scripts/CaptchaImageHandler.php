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


namespace modules\miscellaneous\apps\MobileFrontEnd\scripts;

// version 5 wrapper header

class CaptchaImageHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'captcha_image';
	protected $rawOutput = true;

	public function respond()
	{
		
// end of version 5 wrapper header


$captcha = \App()->ObjectMother->createCaptcha();
$captcha->displayImage();

//  version 5 wrapper footer

	}
}
// end of version 5 wrapper footer
?>

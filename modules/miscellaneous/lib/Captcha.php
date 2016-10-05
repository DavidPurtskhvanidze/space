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


namespace modules\miscellaneous\lib;

class Captcha
{
	function isValid($securityCode)
	{
		$keystring = $this->getKeystring();
		return !is_null($keystring) && strcasecmp($keystring, $securityCode) == 0;
	}
	
	function displayImage()
	{
		require_once("kcaptcha/kcaptcha.php");

		$captcha = new \KCAPTCHA();
		$this->setKeystring($captcha->getKeyString());
	}
	
	function setKeystring($keystring)
	{
		\App()->Session->setValue('captcha_keystring', $keystring);
	}
	
	function getKeystring()
	{
		return \App()->Session->getValue('captcha_keystring');
	}
	
	function isEnabled()
	{
		return true;
	}
}

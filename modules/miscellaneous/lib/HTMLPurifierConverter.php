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

require_once("htmlpurifier/HTMLPurifier.standalone.php");

class HTMLPurifierConverter
{
	function __construct($cacheDir)
	{
		$config = \HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8'); 
		$config->set('HTML.Doctype', 'XHTML 1.0 Strict'); 
		$config->set('Cache.SerializerPath', $cacheDir);
		$config->set('Cache.SerializerPermissions', 0777);
		$this->purifier = new \HTMLPurifier($config);
	}
	
	function getConverted($string)
	{
		if(gettype($string) === "string" && (strrpos($string, ">" ) !== false || strrpos($string, "\"") !== false))
		{
			return $this->purifier->purify($string);
		}
		return $string;
	}
}

?>

<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\Http;

class UnavailableException extends Exception
{
	public function __construct($text = "503 Service Unavailable")
	{
		parent::__construct(503);
		$this->ScreenText = $text;
	}
}

<?php
/**
 *
 *    Module: smarty_based_template_processor v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: smarty_based_template_processor-7.5.0-1
 *    Tag: tags/7.5.0-1@19835, 2016-06-17 13:21:56
 *
 *    This file is part of the 'smarty_based_template_processor' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\smarty_based_template_processor\lib;

class ThrowFileNotFoundExceptionAction
{
	public function execute($file)
	{
		throw new FileNotFoundException($file);
	}
}
class FileNotFoundException extends \Exception
{
	private $notFoundFileName;
	public function __construct($filename)
	{
		$this->notFoundFileName = $filename;
	}
	public function getNotFoundFileName()
	{
		return $this->notFoundFileName;
	}
}

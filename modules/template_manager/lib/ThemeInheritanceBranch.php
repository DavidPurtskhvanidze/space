<?php
/**
 *
 *    Module: template_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: template_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19839, 2016-06-17 13:22:09
 *
 *    This file is part of the 'template_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\template_manager\lib;

class ThemeInheritanceBranch implements \Iterator
{
	private $branch = array();
	private $index = 0;
	
	public function __construct($theme)
	{
		array_unshift($this->branch, $theme);
		while($theme->hasParentTheme())
		{
			$theme = $theme->getParentTheme();
			array_unshift($this->branch, $theme);
		}
	}
	
	public function rewind()
	{
		$this->index = 0;
	}
	
	public function current()
	{
		return $this->branch[$this->index];
	}
	
	public function next()
	{
		$this->index++;
	}
	
	public function key()
	{
		return $this->index;
	}
	
	public function valid()
	{
		return isset($this->branch[$this->index]);
	}
}

?>

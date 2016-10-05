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

class ThemeDirResource
{
	private $dirName;
	/**
	 * @var Theme
	 */
	private $theme;

	public function setDirName($dirName)
	{
		$this->dirName = $dirName;
	}
	public function setTheme($theme)
	{
		$this->theme = $theme;
	}

	public function getTemplate($tpl_name, &$tpl_source, $smarty_obj)
	{
		$template = $this->theme->getTemplate($this->dirName, $tpl_name);
		$tpl_source = $template->getContent();
		return true;
	}
	public function getTimestamp($tpl_name, &$tpl_timestamp, $smarty_obj)
	{
		$template = $this->theme->getTemplate($this->dirName, $tpl_name);
		$tpl_timestamp = $template->getLastModifiedTime();
		return true;
	}
	public function getSecure($tpl_name, &$smarty_obj)
	{
		return true;
	}
	public function getTrusted($tpl_name, &$smarty_obj)
	{
	}
}

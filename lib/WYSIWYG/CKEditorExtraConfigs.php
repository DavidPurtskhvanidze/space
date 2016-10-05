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


namespace lib\WYSIWYG;

class CKEditorExtraConfigs implements \lib\WYSIWYG\ICKEditorExtraConfigs
{

	protected $siteUrl;

	public function __construct()
	{
		$this->siteUrl = \App()->SystemSettings['SiteUrl'] . '/';
	}

	public function getConfigs()
	{
		return array();
	}

}

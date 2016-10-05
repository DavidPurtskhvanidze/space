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


namespace modules\kcfinder\apps\AdminPanel;
 
class CKEditorExtraConfigs extends \lib\WYSIWYG\CKEditorExtraConfigs
{

	public function getConfigs()
	{
		$configs = array(
			'filebrowserBrowseUrl'		=> $this->siteUrl . 'system/kcfinder/browse/?type=files',
			'filebrowserImageBrowseUrl'	=> $this->siteUrl . 'system/kcfinder/browse/?type=images',
			'filebrowserFlashBrowseUrl'	=> $this->siteUrl . 'system/kcfinder/browse/?type=flash',
			'filebrowserUploadUrl'		=> $this->siteUrl . 'system/kcfinder/upload/?type=files',
			'filebrowserImageUploadUrl'	=> $this->siteUrl . 'system/kcfinder/upload/?type=images',
			'filebrowserFlashUploadUrl'	=> $this->siteUrl . 'system/kcfinder/upload/?type=flash'
		);
		return $configs;
	}
}

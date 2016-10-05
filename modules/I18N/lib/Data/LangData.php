<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\lib\Data;

class LangData
{
	var $id;
	var $caption;	
	var $meta = array();
	var $error_text;	
	
	function __construct()
	{
		$this->meta = array
		(
			'active'				=> false,
			'is_default'			=> false,
			'theme'					=> null,
			'mobile_theme'			=> null,
			'admin_theme'			=> null,
			'date_format'			=> null,
			'decimal_separator'		=> null,
			'thousands_separator'	=> null,
		);
	}
	
	public static function createLangDataFromClient($lang_data)
	{
		$langData = new LangData();
		$langData->setID($lang_data['languageId']);
		$langData->setCaption($lang_data['caption']);
		$langData->setActive(isset($lang_data['active']) ? $lang_data['active'] : null);
		$langData->setTheme($lang_data['theme']);
		$langData->setMobileTheme($lang_data['mobile_theme']);
		$langData->setAdminTheme($lang_data['admin_theme']);
		$langData->setDateFormat($lang_data['date_format']);
		$langData->setDecimalSeparator($lang_data['decimal_separator']);
		$langData->setThousandsSeparator($lang_data['thousands_separator']);
		
		return $langData;
	}
	
	function setID($id) 								{ $this->id = $id; }
	function setCaption($caption) 						{ $this->caption = $caption; }
	function setActive($active) 						{ $this->meta['active'] = $active; }
	function setTheme($theme) 							{ $this->meta['theme'] = $theme; }
	function setMobileTheme($mobile_theme) 				{ $this->meta['mobile_theme'] = $mobile_theme; }
	function setAdminTheme($admin_theme) 				{ $this->meta['admin_theme'] = $admin_theme; }
	function setDateFormat($date_format) 				{ $this->meta['date_format'] = $date_format; }
	function setDecimalSeparator($decimal_separator) 	{ $this->meta['decimal_separator'] = $decimal_separator; }
	function setThousandsSeparator($thousands_separator){ $this->meta['thousands_separator'] = $thousands_separator; }
	
	function getID() 				{ return $this->id; }
	function getCaption() 			{ return $this->caption; }
	function getActive() 			{ return $this->meta['active']; }
	function getTheme() 			{ return $this->meta['theme']; }
	function getMobileTheme() 		{ return $this->meta['mobile_theme']; }
	function getAdminTheme() 		{ return $this->meta['admin_theme']; }
	function getDateFormat() 		{ return $this->meta['date_format']; }
	function getDecimalSeparator() 	{ return $this->meta['decimal_separator']; }
	function getThousandsSeparator(){ return $this->meta['thousands_separator']; }
	
	
	function createLangDataFromServer($lang_data)
	{
		$langData = new LangData();
		
		$langData->setID($lang_data['lang_id']);
		$langData->setCaption($lang_data['name']);
		$langData->setMeta($lang_data['meta']);
		$langData->setErrorText($lang_data['error_text']);
		
		return $langData;
	}
	
	function setMeta($meta) 	 		{ if(!empty($meta)) $this->meta = array_merge($this->meta, unserialize($meta)); }
	function setErrorText($error_text)	{ $this->error_text = $error_text; }
	
	function getMeta() 		{ return serialize($this->meta); }
	function getErrorText() { return $this->error_text; }	
}

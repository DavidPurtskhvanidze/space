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


namespace modules\I18N\lib;

require_once "Translation2/Translation2.php";
require_once "Translation2/Admin.php";
require_once 'Translation2/Admin/Container/xml_with_caching.php';

class Translation2AdminFactory
{
	var $context;
	
	function setContext($context)
	{
		$this->context = $context;
	}
	
	function createTrAdmin($file_name, $save_on_shutdown = false)
	{
		list($driver, $options) = $this->_getTrMetaData($file_name, $save_on_shutdown);

		$translation2_Admin = new \Translation2_Admin();
		$tr_admin = $translation2_Admin->factory($driver, $options);
		
		return $tr_admin;
	}
	
	function _getTrMetaData($file_name, $save_on_shutdown)
	{
		$driver = 'XML_WITH_CACHING';

		$options = array
		(
			'filename' => $file_name,
			'save_on_shutdown' => $save_on_shutdown,
			'cache_dir' => \App()->FileSystem->getWritableCacheDir("languages"),
		);
		
		return array($driver, $options);
	}
}

?>

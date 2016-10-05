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

class Initializer extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'kcfinder';
	
	function respond()
	{
	}
	
	function autoload($class) {
		$vendorLibs = \App()->SystemSettings['VendorLibs'];

		if ($class == "uploader")
			require PATH_TO_ROOT . $vendorLibs . $this->moduleName ."/core/uploader.php";
		elseif ($class == "browser")
			require PATH_TO_ROOT . $vendorLibs . $this->moduleName ."/core/browser.php";
		elseif (file_exists(PATH_TO_ROOT . $vendorLibs . $this->moduleName ."/core/types/$class.php"))
		{
			require PATH_TO_ROOT . $vendorLibs . $this->moduleName ."/core/types/$class.php";
		}
		elseif (file_exists(PATH_TO_ROOT . $vendorLibs . $this->moduleName ."/lib/class_$class.php"))
		{
			require PATH_TO_ROOT . $vendorLibs . $this->moduleName ."/lib/class_$class.php";
		}
		elseif (file_exists(PATH_TO_ROOT . $vendorLibs . $this->moduleName ."/lib/helper_$class.php"))
			require PATH_TO_ROOT . $vendorLibs . $this->moduleName ."/lib/helper_$class.php";
	}
	
	function initialize()
	{
		$frontEndSiteUrl = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl');
		$uploadDir = \App()->SystemSettings['KCFinderUploadDir'];
		$_SESSION['KCFINDER'] = array(
			'disabled' => false,
			'uploadURL' => $frontEndSiteUrl . '/' . $uploadDir,
			'uploadDir' => PATH_TO_ROOT . $uploadDir
		);
		spl_autoload_register(array($this,'autoload'));
	}
}

?>

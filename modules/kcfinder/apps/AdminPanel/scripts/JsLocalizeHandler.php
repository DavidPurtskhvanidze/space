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


namespace modules\kcfinder\apps\AdminPanel\scripts;

class JsLocalizeHandler extends \modules\kcfinder\apps\AdminPanel\Initializer
{

	protected $moduleName = 'kcfinder';
	protected $functionName = 'js-localize';
	protected $rawOutput = true;

	function respond()
	{
		$this->initialize();

		$input = new \input();
		if (!isset($input->get['lng']) || ($input->get['lng'] == 'en'))
		{
			header("Content-Type: text/javascript");
			die;
		}
		$file = PATH_TO_ROOT . $vendorLibs . $this->moduleName . "/lang/" . $input->get['lng'] . ".php";

		$files = \dir::content(PATH_TO_ROOT . $vendorLibs . $this->moduleName . "/lang", array(
					'types' => "file",
					'pattern' => '/^.*\.php$/'
		));

		if (!in_array($file, $files))
		{
			header("Content-Type: text/javascript");
			die;
		}
		$mtime = filemtime($file);
		if ($mtime)
			\httpCache::checkMTime($mtime);

		require $file;
		header("Content-Type: text/javascript; charset={$lang['_charset']}");
		foreach ($lang as $english => $native)
			if (substr($english, 0, 1) != "_")
				echo "browser.labels['" . \text::jsValue($english) . "']=\"" . \text::jsValue($native) . "\";";
	}

}

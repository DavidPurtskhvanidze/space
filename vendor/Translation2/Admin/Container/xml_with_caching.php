<?php

require_once 'xml.php';

class Translation2_Admin_Container_xml_with_caching extends Translation2_Admin_Container_xml
{
	function _setDefaultOptions()
	{
		parent::_setDefaultOptions();
		$this->options['cache_dir'] = "";
	}

	function _loadFile()
	{
		if ($this->doesValidCacheExist($this->_filename))
		{
			$this->_data = $this->getCachedData($this->_filename);
		}
		else
		{
			parent::_loadFile();
			$this->setCache($this->_filename, $this->_data);
		}
	}

	private function getCachedData($filename)
	{
		return include_once  $this->getCacheFileName($filename);
	}
	private function setCache($filename, $data)
	{
        $pathToFile = $this->getCacheFileName($filename);
        file_put_contents($pathToFile, '<?php return ' . var_export($data, true) . ';');
        if (function_exists('opcache_invalidate')) opcache_invalidate($pathToFile);
	}
	private function doesValidCacheExist($filename)
	{
		return is_file($this->getCacheFileName($filename)) && (filemtime($this->getCacheFileName($filename)) > filemtime($filename));
	}
	private function getCacheFileName($filename)
	{
		return $this->options['cache_dir'] . "/" . basename($filename) . ".cache";
	}
}

<?php
/**
 *
 *    Module: export_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19779, 2016-06-17 13:19:16
 *
 *    This file is part of the 'export_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_listings\lib;

class ExportListingsEnvironment
{
	private $basedir;
	private $picturesDir = "pictures/";
	private $videosDir = "videos/";
	private $filesDir = "files/";
	private $xlsFileName = "export_data.xls";
	private $fileSystem;

	public function setFileSystem($fileSystem)
	{
		$this->fileSystem = $fileSystem;
	}
	public function setBasedir($basedir)
	{
		$this->basedir = $basedir;
	}
	public function prepare()
	{
		if (is_dir($this->basedir)) $this->fileSystem->removeRecursively($this->basedir);
		$dirs = array($this->basedir, $this->basedir . $this->picturesDir, $this->basedir . $this->videosDir, $this->basedir . $this->filesDir);
		array_walk($dirs, array($this, 'createDir'));
	}
	private function createDir($dir)
	{
		mkdir($dir, 0777);
		chmod($dir, 0777);
	}
	public function clear()
	{
		$this->fileSystem->removeRecursively($this->basedir);
	}
}

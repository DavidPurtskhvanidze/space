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

require_once "Archive/Tar.php";

class ExportArchiver
{
	private $removeDir;
	private $name;
	private $filesList;

	public function setRemoveDir($removeDir)
	{
		$this->removeDir = $removeDir;
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	public function setFilesList($filesList)
	{
		$this->filesList = $filesList;
	}
	public function send()
	{
		$tar = new \Archive_Tar($this->name, 'gz');
		$tar->createModify($this->filesList, "", $this->removeDir);
		header("Content-type: application/octet-stream");  
		header("Content-disposition: attachment; filename=" . basename($this->name));
		header("Content-Length: " . filesize($this->name));
		readfile($this->name);
	}
}

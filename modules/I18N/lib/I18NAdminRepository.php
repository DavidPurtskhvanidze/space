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

class I18NAdminRepository
{
	var $adminFactory;
	var $fileHelper;
	var $repository = array();

	function setAdminFactory($adminFactory)
	{
		$this->adminFactory = $adminFactory;
	}

	function setFileHelper($fileHelper)
	{
		$this->fileHelper = $fileHelper;
	}
	
	function load()
	{
		$language_ids = $this->fileHelper->getLanguageIDs();
		
		foreach($language_ids as $language_id)
		{
			$this->repository[$language_id] = null;
		}
	}
	
	function create($language_id)
	{
		$file_path = $this->fileHelper->getFilePathToLangFile($language_id);
		
		$this->fileHelper->createFile($file_path);
		
		$trAdmin = $this->adminFactory->createTrAdmin(realpath($file_path), true);
		
		$this->repository[$language_id] = $trAdmin;
		
		return $trAdmin;
	}
	
	function get($language_id)
	{		
		if (!isset($this->repository[$language_id]))
		{		
			$file_path = $this->fileHelper->getFilePathToLangFile($language_id);
			
			$trAdmin = $this->adminFactory->createTrAdmin($file_path);
			$this->repository[$language_id] = $trAdmin;
		}
		else $trAdmin = $this->repository[$language_id];
		
		return $trAdmin;
	}
	
	function remove($language_id)
	{
		$file_path = $this->fileHelper->getFilePathToLangFile($language_id);
		
		unset($this->repository[$language_id]);
				
		return $this->fileHelper->deleteFile($file_path);
	}
	
	function getLangList()
	{		
		return array_keys($this->repository);
	}
}

?>

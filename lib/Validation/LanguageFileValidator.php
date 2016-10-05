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


namespace lib\Validation;

class LanguageFileValidator
{
	var $dataReflector = null;
	var $errors = array();
	
	function setDataReflector(&$dataReflector)
	{
		$this->dataReflector = $dataReflector;
	}
	
	function isValid($value)
	{
		$trAdminFactory = new \modules\I18N\lib\Translation2AdminFactory();
		$trAdmin = $trAdminFactory->createTrAdmin($value);
		
		if (strpos(strtolower(get_class($trAdmin)), 'translation2_admin') === false)
		{
			$errors[] = 'UPLOADED_LANG_FILE_STRUCTURE_IS_INVALID';
			\core\Logger::error('UPLOADED_LANG_FILE_STRUCTURE_IS_INVALID');
			return false;
		}
		
		$file_langs_list = $trAdmin->getLangs();
		$import_lang_id = (string) $this->dataReflector->get('languageId');
		
		if (!array_key_exists($import_lang_id, $file_langs_list))
		{
			$errors[] = 'UPLOADED_LANG_FILE_DOESNOT_HAVE_NECESSARY_LANGUAGE';
			\core\Logger::error('UPLOADED_LANG_FILE_DOESNOT_HAVE_NECESSARY_LANGUAGE');
			return false;
		}
		
		return true;
	}
	
	function getErrors()
	{
		return array();
	}
}

?>

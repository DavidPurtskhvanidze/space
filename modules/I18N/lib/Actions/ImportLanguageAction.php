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


namespace modules\I18N\lib\Actions;

class ImportLanguageAction
{
	/**
	 * @var \modules\I18N\lib\I18N
	 */
	private $i18n;
	private $lang_file_data;
	private $file_name;
	private $temp_file_path;
	private $file_path;

	function __construct($i18n, $lang_file_data, $temp_dest)
	{
		$this->i18n = $i18n;
		$this->lang_file_data = $lang_file_data;

		$this->file_name = isset($lang_file_data['name']) ? $lang_file_data['name'] : null;
		$this->temp_file_path = isset($lang_file_data['tmp_name']) ? $lang_file_data['tmp_name'] : null;
		$this->file_path = \App()->Path->combine($temp_dest, $this->file_name);
	}

	function canPerform()
	{
		$canPerform = true;

		/**
		 * @var \modules\I18N\apps\AdminPanel\IImportLanguageValidator[] $validators
		 */
		$validators = new \core\ExtensionPoint('modules\I18N\apps\AdminPanel\IImportLanguageValidator');
		foreach ($validators as $validator)
		{
			$validator->setLangFileData($this->lang_file_data);
			$canPerform &= $validator->isValid();
		}

		if (!$canPerform)
		{
			return false;
		}

		$validate = $this->_validate();
		if (!$validate && is_file($this->file_path)) \App()->FileSystem->deleteFile($this->file_path);
		return $validate;
	}

	function perform()
	{
		return $this->i18n->importLangFile($this->file_name, $this->file_path);
	}

	function _validate()
	{
		$wrappedFunctions = new \core\WrappedFunctions();

		if (!$wrappedFunctions->is_uploaded_file($this->temp_file_path))
		{
			\App()->ErrorMessages->addMessage('LANG_FILE_UPLOAD_FAILED');
			return false;
		}

		if (!$wrappedFunctions->move_uploaded_file($this->temp_file_path, $this->file_path))
		{
			\App()->ErrorMessages->addMessage('UPLOADED_LANG_FILE_CANNOT_BE_MOVED', array('pathToLanguagesFolder' => \App()->SystemSettings['I18NSettings_PathToLanguageFiles']));
			return false;
		}

		$languageID = $this->i18n->getFileHelper()->getLanguageIDForFile($this->file_name);
		if ($languageID === false)
		{
			\App()->ErrorMessages->addMessage('UPLOADED_LANG_FILE_NAME_IS_INVALID', array('uploadedFilename' => $this->file_name, 'filenameFormat' => $this->i18n->getContext()->getFileNameTemplateForLanguageFile()));
			return false;
		}

		$lang_file_data = array
		(
			'languageId' => $languageID,
			'lang_file_path' => $this->file_path,
		);

		$validator = $this->i18n->createImportLanguageValidator($lang_file_data);
		return $validator->isValid();
	}
}

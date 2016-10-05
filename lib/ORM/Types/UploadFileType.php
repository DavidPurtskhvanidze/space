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


namespace lib\ORM\Types;


class UploadFileType extends Type
{
	protected $_fileGroup;
	function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->_fileGroup = 'files';
		$this->default_template = 'file.tpl';
	}
	function isEmpty() 
	{
		return parent::isEmpty() && !\App()->UploadFileManager->isFileReadyForUpload($this->property_info['id']);
	}
	function getPropertyVariablesToAssignTypeSpecific()
	{
		return array
		(
			'id' => $this->property_info['id'],
			'value' => array
			(
				'file_url' => \App()->UploadFileManager->getUploadedFileLink($this->property_info['value']),
				'file_name' => \App()->UploadFileManager->getUploadedFileName($this->property_info['value']),
			),
		);
	}
	function getValue()
	{
        return array
		(
			'file_url' 	=> \App()->UploadFileManager->getUploadedFileLink($this->property_info['value']),
			'file_name' => \App()->UploadFileManager->getUploadedFileName($this->property_info['value']),
			'file_id' => $this->property_info['value'],
		);
	}

    private function getDefaultUploadMaxFileSize()
    {
        return (integer) trim(ini_get('upload_max_filesize'));
    }

	public function isValid()
	{
        $uploadMaxFileSize = !empty($this->property_info['max_file_size']) ? $this->property_info['max_file_size'] : $this->getDefaultUploadMaxFileSize();
        \App()->UploadFileManager->setMaxFileSize($uploadMaxFileSize);

		if (
			!\App()->UploadFileManager->isValidUploadedFile($this->property_info['id'])
			|| !\App()->UploadFileManager->uploadDirIsExistsAndWritable($this->_fileGroup)
		)
		{
			$error = \App()->UploadFileManager->getError();
			$this->addValidationError($error['id'], $error['data']);
			return false;
		}

        return true;
	}
	function getSQLValue()
	{
		if (\App()->UploadFileManager->isFileReadyForUpload($this->property_info['id']))
		{
			\App()->UploadFileManager->deleteUploadedFileByID($this->property_info['value']);
			$this->property_info['value'] = \App()->UploadFileManager->uploadFile($this->_fileGroup, $this->property_info['id']);
		}
		return !empty($this->property_info['value']) ? $this->property_info['value'] : null;
	}
	static function getFieldExtraDetails()
	{
		return array
		(
			array
			(
				'id'		=> 'max_file_size',
				'caption'	=> 'Maximum File Size, MB',
				'type'		=> 'float',
				'length'	=> '20',
				'minimum'	=> '0',
				'signs_num' => '2',
				'value' => '',
			),
		);
	}
}

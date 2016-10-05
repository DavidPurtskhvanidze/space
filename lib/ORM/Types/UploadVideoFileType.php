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


class UploadVideoFileType extends UploadFileType
{
    /**
     * @var \modules\field_types\lib\YoutubeFilesStackManager
     */
    private $youTubeFilesStackManager;

	function __construct($property_info)
	{
		parent::__construct($property_info);
        $this->_fileGroup = 'video';
        $this->default_template = 'video.tpl';
        $this->youTubeFilesStackManager = new \modules\field_types\lib\YoutubeFilesStackManager();
	}

	public function isValid()
	{
		if (!\App()->UploadFileManager->isValidUploadedVideoFile($this->property_info['id']))
		{
			$error = \App()->UploadFileManager->getError();
			$this->addValidationError($error['id'], $error['data']);
			return false;
		}
		return parent::isValid();
	}

	public function getPropertyVariablesToAssignTypeSpecific()
	{
		$supportedVideoFormats = \App()->UploadFileManager->getSupportedVideoFileTypes();
		$variablesToAssign = array_merge(parent::getPropertyVariablesToAssignTypeSpecific(), array('supportedVideoFormats' => implode(', ', $supportedVideoFormats)));
		$variablesToAssign['maxFileSize'] = !empty($this->property_info['max_file_size']) ? $this->property_info['max_file_size'] : (integer) trim(ini_get('upload_max_filesize'));
		
		return $variablesToAssign;
	}

    public function getSQLValue()
    {
        parent::getSQLValue();
        if(!empty($this->property_info['value']))
        {
            $this->youTubeFilesStackManager->addFileToStack($this->property_info['value'], $this->object_sid);
        }
        return !empty($this->property_info['value']) ? $this->property_info['value'] : null;
    }

	function getValue()
	{
        $file_info = \App()->UploadFileManager->getUploadedFileInfo($this->property_info['value']);
        return array
		(
			'status' 	=> $file_info['storage_method'] != 'youtube' ? 'pending' : 'uploaded',
			'uploaded' 	=> $file_info['storage_method'] != 'youtube' ? false : true,
			'video_id' => $file_info['saved_file_name'],
			'file_id' => $file_info['sid'],
		);
	}
}

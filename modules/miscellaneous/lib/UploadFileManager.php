<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class UploadFileManager implements \core\IService
{
	public function init(){}
	
	var $max_file_size;
	protected $error;
	private $supportedVideoFileTypes = array('3gp', '3gpp', 'mpeg4', 'avi', 'mov', 'mp4', 'mpegpc', 'flv', 'wmv');

	function setMaxFileSize($max_file_size)
	{
		$this->max_file_size = $max_file_size;
	}
	function isValidUploadedFile($file_id)
	{
		if(
            !empty($this->max_file_size)
            && isset($_FILES[$file_id])
            && ($_FILES[$file_id]['size'] > ($this->max_file_size * 1024 * 1024)
                || in_array($_FILES[$file_id]['error'], array(UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE, UPLOAD_ERR_PARTIAL)
                )
            )
        )
        {
            $this->error = array('id' => 'MAX_FILE_SIZE_EXCEEDED', 'data' => array('maxFileSize' => $this->max_file_size));
            return false;
        }

        return true;
	}
	function isValidUploadedVideoFile($file_id)
	{
		if(!isset($_FILES[$file_id]) || $_FILES[$file_id]['error'] == UPLOAD_ERR_NO_FILE) return true;
		$fileExtension = strtolower(pathinfo($_FILES[$file_id]['name'], PATHINFO_EXTENSION));
		if (!empty($fileExtension) && in_array($fileExtension, $this->supportedVideoFileTypes))
		{
			return true;
		}
		else
		{
			$this->error = array('id' => 'NOT_SUPPORTED_VIDEO_FORMAT', 'data' => array('supportedVideoFormats' => implode(', ', $this->supportedVideoFileTypes)));
		}
	}
	function isFileReadyForUpload($file_id) 
	{
		return !empty($_FILES[$file_id]['name']);
	}
	function uploadFile($fileGroup, $file_id)
	{
		$saved_file_name = $this->getNameForUploadedFile($_FILES[$file_id]['name'], $fileGroup);
		$upload_file_directory = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'];
		$file_name = "$upload_file_directory/$fileGroup/$saved_file_name";
		if (@move_uploaded_file($_FILES[$file_id]['tmp_name'], $file_name))
		{
			return \App()->DB->query("INSERT INTO `core_uploaded_files`(`file_name`, `file_group`, `saved_file_name`) VALUES(?s, ?s, ?s)",
				$_FILES[$file_id]['name'], $fileGroup, $saved_file_name);
		}
	}
	protected function getNameForUploadedFile($filename, $fileGroup)
	{
		$upload_file_directory = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'];
		$fileInfo = pathinfo($filename);
		if (!preg_match('/^[a-z0-9\-_]+$/iu', $fileInfo['filename']))
		{
			$fileInfo['filename'] = base64_encode($fileInfo['filename']);
			$filename = sprintf("%s.%s", $fileInfo['filename'], $fileInfo['extension']);
		}
		$i = 0;
		while (file_exists("$upload_file_directory/$fileGroup/$filename"))
			$filename = sprintf("%s_%s.%s", $fileInfo['filename'], ++$i, $fileInfo['extension']);

		return $filename;
	}
	function deleteUploadedFileByID($file_id)
	{
		$wrappedFunctions = new \core\WrappedFunctions();
		$file_info = $this->getUploadedFileInfo($file_id);
		if (!empty($file_info))
		{
			if ($file_info['storage_method'] == 'file_system')
			{
				$upload_file_directory = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'];
				$file_name = \App()->Path->combine($upload_file_directory, $file_info['file_group'], $file_info['saved_file_name']);
				if ($wrappedFunctions->file_exists($file_name)) $wrappedFunctions->unlink($file_name);
                $youTubeFilesStackManager = new \modules\field_types\lib\YoutubeFilesStackManager();
                $youTubeFilesStackManager->deleteFileFromStack($file_info['sid']);
			}
            elseif($file_info['storage_method'] == 'youtube')
            {
                $this->deleteFileFromYouTube($file_info['saved_file_name']);
            }
			\App()->DB->query("DELETE FROM `core_uploaded_files` WHERE sid = ?n", $file_id);
		}
	}

    function deleteFileFromYouTube($videoId)
    {
        $youTubeVideoManager = new \modules\field_types\lib\YouTubeVideoManager();
        if ($youTubeVideoManager->getDefinedAccessToken())
            $youTubeVideoManager->delete($videoId);
    }
	function getError()
	{
		return $this->error;
	}
	function getUploadedFileLink($uploaded_file_id)
	{
		$file_name = $this->getUploadedFilePath($uploaded_file_id);
        if (empty($file_name)) return null;
		return \App()->SystemSettings['SiteUrl'] . "/" . $file_name;
	}
    function getUploadedFilePath($uploaded_file_id)
    {
        $file_info = $this->getUploadedFileInfo($uploaded_file_id);
        if (empty($file_info)) return null;
        return PATH_TO_ROOT . \App()->SystemSettings['FilesDir'] . $file_info['file_group'] . "/" . $file_info['saved_file_name'];
    }

    function updateUploadedFileYouTube($local_file_id, $youtube_file_id)
    {
        $wrappedFunctions = new \core\WrappedFunctions();
        $file_info = $this->getUploadedFileInfo($local_file_id);
        if (!empty($file_info))
        {
            if ($file_info['storage_method'] == 'file_system')
            {
                \App()->DB->query("UPDATE `core_uploaded_files` SET `storage_method` = 'youtube', `saved_file_name` = ?s WHERE `sid`=?n", $youtube_file_id, $local_file_id);
                $upload_file_directory = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'];
                $file_name = \App()->Path->combine($upload_file_directory, $file_info['file_group'], $file_info['saved_file_name']);
                if ($wrappedFunctions->file_exists($file_name)) $wrappedFunctions->unlink($file_name);
                $youTubeFilesStackManager = new \modules\field_types\lib\YoutubeFilesStackManager();
                $youTubeFilesStackManager->deleteFileFromStack($file_info['sid']);
            }
        }
    }

	function getUploadedFileMime($uploaded_file_id)
	{
		$file_info = $this->getUploadedFileInfo($uploaded_file_id);
		if (empty($file_info)) return null;
		$file_name = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'] . $file_info['file_group'] . "/" . $file_info['saved_file_name'];
		if (function_exists("finfo_file"))
		{
			$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
			$mime = finfo_file($finfo, $file_name);
			finfo_close($finfo);
			return $mime;
		}
		elseif (function_exists("mime_content_type"))
		{
			return mime_content_type($file_name);
		}
		elseif (!stristr(ini_get("disable_functions"), "shell_exec"))
		{
		  // http://stackoverflow.com/a/134930/1593459
		  $file_name = escapeshellarg($file_name);
		  $mime = shell_exec("file -bi " . $file_name);
		  return $mime;
		}
		else
		{
		  return null;
		}
	}
	function getUploadedFileName($uploaded_file_id)
	{
		$file_info = $this->getUploadedFileInfo($uploaded_file_id);
		return isset($file_info['file_name']) ? $file_info['file_name'] : null;
	}
	function getUploadedFileGroup($uploaded_file_id)
	{
		$file_info = $this->getUploadedFileInfo($uploaded_file_id);
		return isset($file_info['file_group']) ? $file_info['file_group'] : null;
	}
	function getUploadedFileInfo($uploaded_file_id)
	{
		$file_info = \App()->DB->query("SELECT * FROM `core_uploaded_files` WHERE sid = ?n", $uploaded_file_id);
		return !empty($file_info) ? array_pop($file_info) : null;
	}
	function doesFileExistByID($uploaded_file_id)
	{
		$file_info = \App()->DB->query("SELECT count(*) FROM `core_uploaded_files` WHERE sid = ?n", $uploaded_file_id);
		return $file_info[0]['count(*)'] > 0;
	}
	public function copyFile($fileId, $dest)
	{
		$file_info = $this->getUploadedFileInfo($fileId);
		$file_name = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'] . $file_info['file_group'] . "/" . $file_info['saved_file_name'];
		if (file_exists($file_name) && is_file($file_name)) copy($file_name, $dest);
	}
	public function uploadDirIsExistsAndWritable($fileGroup)
	{
		$uploadFileDirectory = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'];
		if(!file_exists($uploadFileDirectory))
		{
			$this->error = array('id' => 'UPLOAD_DIR_NOT_EXIST', 'data' => array('uploadFilesDir' => $uploadFileDirectory));
			return false;
		}
		if (!is_writeable($uploadFileDirectory))
		{
			$this->error = array('id' => 'UPLOAD_DIR_NOT_WRITABLE', 'data' => array('uploadFilesDir' => $uploadFileDirectory));
			return false;
		}
		$uploadFileDirectory = $uploadFileDirectory . DIRECTORY_SEPARATOR . $fileGroup;
		if(!file_exists($uploadFileDirectory))
		{
			$this->error = array('id' => 'UPLOAD_DIR_NOT_EXIST', 'data' => array('uploadFilesDir' => $uploadFileDirectory));
			return false;
		}
		if (!is_writeable($uploadFileDirectory))
		{
			$this->error = array('id' => 'UPLOAD_DIR_NOT_WRITABLE', 'data' => array('uploadFilesDir' => $uploadFileDirectory));
			return false;
		}
		return true;
	}

	public function getSupportedVideoFileTypes()
	{
		return $this->supportedVideoFileTypes;
	}

	public function getUploadedFile($id)
	{
		return new UploadedFile($id);
	}
}

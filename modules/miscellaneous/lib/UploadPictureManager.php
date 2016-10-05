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

class UploadPictureManager extends UploadFileManager implements \core\IService
{
	var $height;
	var $width;
	var $storage_method;

	public function init(){}

	function setWidth($width)
	{
		$this->width = $width;
	}
	function setHeight($height)
	{
		$this->height = $height;
	}
	function setStorageMethod($storage_method)
	{
		$this->storage_method = $storage_method;
	}
	function isValidUploadedPictureFile($file_id)
	{
		if (empty($_FILES[$file_id]['tmp_name'])) return true;
		$image_info = getimagesize($_FILES[$file_id]['tmp_name']);		// $image_info['2'] = 1 {GIF}, 2 {JPG}, 3 {PNG}, 4 {SWF}, 5 {PSD}, 6 {BMP}, 7 {TIFF}, 8 {TIFF}, 9 {JPC}, 10 {JP2}, 11 {JPX}		
		if ( $image_info['2'] >= 1 && $image_info['2'] <= 3 )
		{
			return true;
		}
		else
		{
			$this->error = array('id' => 'NOT_SUPPORTED_IMAGE_FORMAT', 'data' => array());
			return false;
		}
	}
	function uploadFile($fileGroup, $file_id)
	{
		$image_file_name = $_FILES[$file_id]['tmp_name'];
		$image_info = getimagesize($image_file_name);		// $image_info['2'] = 1 {GIF}, 2 {JPG}, 3 {PNG}, 4 {SWF}, 5 {PSD}, 6 {BMP}, 7 {TIFF}, 8 {TIFF}, 9 {JPC}, 10 {JP2}, 11 {JPX}
		if ($image_info['2'] == 1)
		{
			$image_resource = imagecreatefromgif($image_file_name);
		}
		elseif ($image_info['2'] == 2)
		{
			$image_resource = imagecreatefromjpeg($image_file_name);
		}
		else
		{
			$image_resource = imagecreatefrompng($image_file_name);
		}
		$picture_max_size['width']  = $this->width;
		$picture_max_size['height'] = $this->height;
		$picture_resource = $this->getResizedImageResource($image_resource, $picture_max_size);
		imagedestroy($image_resource);
		if ($this->storage_method == 'database')
		{
			$id = $this->_uploadPictureToDB($fileGroup, $file_id, $picture_resource);
		}
		else
		{
			$id = $this->_uploadPictureToFileSystem($fileGroup, $file_id, $picture_resource);
		}
		imagedestroy($picture_resource);
		return $id;
	}
	private function _uploadPictureToDB($fileGroup, $file_id, $picture_resource)
	{
		ob_start();
		imagejpeg($picture_resource);
		$picture_content = ob_get_contents();
		ob_end_clean();
		return \App()->DB->query("INSERT INTO `core_uploaded_files`(`file_name`, `file_group`, `file_content`, `storage_method`) VALUES(?s, ?s, ?b, ?s)",
			$_FILES[$file_id]['name'], $fileGroup, $picture_content, $this->storage_method);
	}
	
	function _uploadPictureToFileSystem($fileGroup, $file_id, $picture_resource)
	{
		$saved_file_name = $this->getNameForUploadedPicture($_FILES[$file_id]['name'], $fileGroup);
		$upload_file_directory = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'];
		$file_name = "$upload_file_directory{$fileGroup}/$saved_file_name";
		if (@imagejpeg($picture_resource, $file_name))
		{
			return \App()->DB->query("INSERT INTO `core_uploaded_files`(`file_name`, `file_group`, `saved_file_name`) VALUES(?s, ?s, ?s)",
				$_FILES[$file_id]['name'], $fileGroup, $saved_file_name);
		}
	}
	protected function getNameForUploadedPicture($filename, $fileGroup)
	{
		$upload_file_directory = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'];
		$p = pathinfo($filename);
		$filename = $p['filename'] . ".jpg";
		$i = 0;
		while (file_exists("$upload_file_directory/$fileGroup/$filename"))
			$filename = sprintf("%s_%s.%s", $p['filename'], ++$i, "jpg");
		return $filename;
	}
	function getResizedImageResource($image_resource, $image_max_size)
	{
		$image_width = imagesx($image_resource);	$image_height = imagesy($image_resource);
		if (($image_width > $image_max_size['width']) || ($image_height > $image_max_size['height']))
		{
			$k_w = $image_width / $image_max_size['width'];
			$k_h = $image_height / $image_max_size['height'];
			$k = max($k_w, $k_h);
			$picture_width = round($image_width / $k);
			$picture_height = round($image_height / $k);
		}
		else
		{
			$picture_width = $image_width;
			$picture_height = $image_height;
		}
		$resized_image_resource = imagecreatetruecolor($picture_width, $picture_height);
		imagecopyresampled($resized_image_resource, $image_resource, 0, 0, 0, 0, $picture_width, $picture_height, $image_width, $image_height);
		return $resized_image_resource;
	}
	function getUploadedPictureInfo($picture_id)
	{
		return $this->getUploadedFileInfo($picture_id);
	}
	function getUploadedFileLink($uploaded_file_id)
	{
		$file_info = $this->getUploadedFileInfo($uploaded_file_id);
		if (empty($file_info)) return null;
		if ($file_info['storage_method'] == 'file_system')
		{
			$file_name = PATH_TO_ROOT . \App()->SystemSettings['FilesDir'] . $file_info['file_group'] . "/" . $file_info['saved_file_name'];
		}
		else
		{
			$file_name = "system/miscellaneous/uploaded_file/?file_id=$uploaded_file_id";
		}
		return \App()->SystemSettings['SiteUrl'] . "/" . $file_name;
	}
	public function uploadDirIsExistsAndWritable($fileGroup)
	{
//		if ($file_info['storage_method'] == 'file_system')
//		{
//			return parent::uploadDirIsExistsAndWritable($fileGroup);
//		}
		return true;
	}
}

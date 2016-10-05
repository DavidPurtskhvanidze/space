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


namespace lib\DataTransceiver\Import;

abstract class InputDataSourceTable implements IInputDataSource
{
	private $fileReader;
	private $fieldsScheme;

	private $unpackers = array(
		'gz' => '\modules\miscellaneous\lib\TarGzUnpacker',
		'zip' => '\modules\miscellaneous\lib\ZipUnpacker'
	);

	public function setFileReader($fileReader)
	{
		$this->fileReader = $fileReader;
	}

	public function getNext()
	{
		return @array_combine($this->fieldsScheme, $this->fileReader->getNext());
	}

	public function isEmpty()
	{
		return $this->fileReader->isEmpty();
	}

	public function getFieldsScheme()
	{
		return $this->fieldsScheme;
	}

	public function init()
	{
		$this->fieldsScheme = $this->fileReader->getNext();
		$this->fieldsScheme = array_map('trim', $this->fieldsScheme);
		if (empty($this->fieldsScheme))
			throw new \lib\DataTransceiver\TransceiveFailedException('INVALID_DATA_FORMAT');
	}

	protected function isArchive($fileName)
	{
		return array_key_exists($this->getFileExtension($fileName), $this->unpackers);
	}

	protected function getUnpackedFilePath($filePath, $fileExtension)
	{
		$importFilesDir = PATH_TO_ROOT . \App()->SystemSettings['ImportFilesDir'];
		if (is_dir($importFilesDir))
		{
			\App()->FileSystem->removeRecursively($importFilesDir);
		}
		\App()->FileSystem->getWritableDir($importFilesDir);

		$unpackerClassName = $this->unpackers[$this->getFileExtension($filePath)];
		$unpacker = new $unpackerClassName();
		$unpacker->unpack($filePath, $importFilesDir);
		$files = \App()->FileSystem->getFiles($importFilesDir, "/.*{$fileExtension}$/");
		if (empty($files))
		{
			throw new \lib\DataTransceiver\TransceiveFailedException('FILE_NOT_FOUND_IN_ARCHIVE');
		}
		else
		{
			return \App()->Path->combine($importFilesDir, $files[0]);
		}
	}

	private function getFileExtension($fileName)
	{
		return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
	}

	/**
	 * @var IImportConfig
	 */
	protected $config;
	public function setConfig($config)
	{
		$this->config = $config;
	}
}

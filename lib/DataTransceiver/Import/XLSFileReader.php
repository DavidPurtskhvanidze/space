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

class XLSFileReader implements FileReader
{
	private $currentRow;
	private $numRows;
	private $numCols;
	private $data;

	public function __construct($filePath)
	{
		if (!is_file($filePath) || !is_readable($filePath))
			throw new \lib\DataTransceiver\TransceiveFailedException('INVALID_FILE');

		require_once('Excel/reader.php');
		$data = new \Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		$data->read($filePath);
		$this->data = $data->sheets[0]['cells'];
		$this->numRows = min($data->sheets[0]['numRows'], count($this->data));
		$this->numCols = $data->sheets[0]['numCols'];
		$this->currentRow = 1;
	}

	public function getNext()
	{
		$row = array();
		for ($i = 1; $i <= $this->numCols; $i++)
		{
			$row[] = isset($this->data[$this->currentRow][$i]) ? $this->data[$this->currentRow][$i] : null;
		}
		$this->currentRow++;
		return $row;
	}

	public function isEmpty()
	{
		return $this->currentRow > $this->numRows;
	}
}

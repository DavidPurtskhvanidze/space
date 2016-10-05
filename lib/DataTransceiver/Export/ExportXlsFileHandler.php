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


namespace lib\DataTransceiver\Export;

// need refactoring

class ExportXlsFileHandler
{
	var $currentRowNumber = 0;
	var $xlsWriter;
	var $sheet;
	var $headRowData;
	var $initialized = false;
	var $filename;

	public function setFilename($filename)
	{
		$this->filename = $filename;
	}

	function init()
	{
		require_once("Excel/Writer.php");
		$xlsWriter = new \Spreadsheet_Excel_Writer($this->filename);
        $xlsWriter->setTempDir(sys_get_temp_dir());
		$xlsWriter->setVersion(8);
		if (is_null($this->filename)) $xlsWriter->send('export_data.xls');
		$xlsWriter->setCustomColor(10, 120, 170, 220);
		$sheet = $xlsWriter->addWorksheet();
		$sheet->setInputEncoding('utf-8');
		$this->setXlsWriter($xlsWriter);
		$this->setSheet($sheet);
		$this->writeHeadRow($this->headRowData);
		$this->initialized = true;
	}
	
	function finalize()
	{
		$this->xlsWriter->close();
	}
	function setXlsWriter($xlsWriter)
	{
		$this->xlsWriter = $xlsWriter;
	}
	
	function setSheet($sheet)
	{
		$this->sheet = $sheet;
	}
	
	function getSheet()
	{
		return $this->sheet;
	}
	
	function setCurrentRowNumber($currentRowNumber)
	{
		$this->currentRowNumber = $currentRowNumber;
	}
	
	function getCurrentRowNumber()
	{
		return $this->currentRowNumber;
	}
	
	function writeRow($data)
	{
		if (!$this->initialized) $this->init();
		$rowNumber = $this->getCurrentRowNumber();
		$sheet = $this->getSheet();
		$sheet->writeRow($rowNumber, 0, $data);
		$this->setCurrentRowNumber($rowNumber + 1);
	}
	
	function setHeadRowData($data)
	{
		$this->headRowData = $data;
	}
	function writeHeadRow($data)
	{
		$head_format = $this->xlsWriter->addFormat();
		$head_format->setAlign('center');
		$head_format->setFgColor(10);
		$head_format->setBold();
		$head_format->setBorder(1);
		
		$rowNumber = $this->getCurrentRowNumber();
		$sheet = $this->getSheet();
		$sheet->writeRow($rowNumber, 0, $data,$head_format);
		$this->setCurrentRowNumber($rowNumber + 1);
	}
	
	function handle($exportedListing)
	{
		$this->writeRow($exportedListing->getData());
	}
}

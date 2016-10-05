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

class ImportedExcelFile {
	
	var $filename;	
	var $parse_date = true;
		
	function setFileName($filename) {		
		$this->filename = $filename;		
	}
	
	function setParseDate($parse_date) {
		$this->parse_date = $parse_date;
	}
	
	function getTable() {
		require_once('Excel/reader.php');
		$excel_reader = new \Spreadsheet_Excel_Reader();
		$excel_reader->setOutputEncoding('utf-8');
		$excel_reader->setRowColOffset(0);		
		// to do not parse cells data as date
		if (!$this->parse_date)
			$excel_reader->dateFormats = array();
		$excel_reader->read($this->filename);
		$table = $excel_reader->sheets[0]['cells'];		
		return $table;		
	}
	
}

?>

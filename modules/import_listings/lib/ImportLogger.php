<?php
/**
 *
 *    Module: import_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19787, 2016-06-17 13:19:36
 *
 *    This file is part of the 'import_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_listings\lib;

class ImportLogger extends \lib\DataTransceiver\Import\ImportLogger
{
	private $line = 0;
	private $added = 0;
	private $deleted = 0;
	private $updated = 0;
	private $ignored = 0;
	private $optionsAdded = 0;
	private $treeValuesAdded = 0;
	private $listValuesAdded = 0;
	private $picturesAdded = 0;
	private $warnings = array();
	private $timerStartTime;
	private $timeElapsed;

	public function __construct()
	{
		$this->timerStartTime = microtime();
	}
	public function logAdd($amount = 1)
	{
		$this->added += $amount;
		$this->line += $amount;
	}
	public function logUpdate($amount = 1)
	{
		$this->updated += $amount;
		$this->line += $amount;
	}
	public function logIgnore($amount = 1)
	{
		$this->ignored += $amount;
		$this->line += $amount;
	}
	public function logDelete($amount = 1)
	{
		$this->deleted += $amount;
	}
	public function logOptionAdd($amount = 1)
	{
		$this->optionsAdded += $amount;
	}
	public function logTreeValueAdd($amount = 1)
	{
		$this->treeValuesAdded += $amount;
	}
	public function logListValueAdd($amount = 1)
	{
		$this->listValuesAdded += $amount;
	}
	public function logPictureAdd($amount = 1)
	{
		$this->picturesAdded += $amount;
	}
	public function stopTimer()
	{
		$endTime = microtime();
		$this->timeElapsed = round($this->getFloatTime($endTime) - $this->getFloatTime($this->timerStartTime), 5);
	}
	private function getFloatTime($timeStr)
	{
		list($usec, $sec) = explode(" ", $timeStr);
		return ((float)$usec + (float)$sec);
	}
	public function getLog()
	{
		return array
		(
			'total' => $this->line,
			'added' => $this->added,
			'updated' => $this->updated,
			'deleted' => $this->deleted,
			'ignored' => $this->ignored,
			'optionsAdded' => $this->optionsAdded,
			'treeValuesAdded' => $this->treeValuesAdded,
			'listValuesAdded' => $this->listValuesAdded,
			'picturesAdded' => $this->picturesAdded,
			'warnings' => $this->warnings,
			'timeElapsed' => $this->timeElapsed,
			'numberOfImportedRecords' => $this->numberOfImportedRecords,
			'numberOfInvalidRecords' => $this->numberOfInvalidRecords,
			'recordsNumber' => $this->recordNumber,
			'errors' => $this->errors,
		);
	}
}

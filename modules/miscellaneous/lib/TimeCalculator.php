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

class TimeCalculator {

	var $start_time;
	var $caption;
	var $caller_info;

	function __construct($caption) {
		$this->start_time = microtime();
		$this->caption = $caption;
		@list($this->caller_info, $this->caller_caller_info) = debug_backtrace();
		
	}

	function getElapsedTime($label = null) {
		list($caller_info, $caller_caller_info) = debug_backtrace();
		$end_time = microtime();
		$elapsed_time = 1000 * round($this->getFloatTime($end_time) - $this->getFloatTime($this->start_time), 5);
		$file = basename($caller_info['file']);
		$line = $caller_info['line'];
		$caller_file = basename($caller_caller_info['file']);
		$caller_line = $caller_caller_info['line'];
//		$tm = "<b>{$this->caption}</b> was executed in <b>$elapsed_time</b> miliseconds <br>\r\n";
		$tm = array
		(
			'elapsed_time' => $elapsed_time,
			'caption' => !is_null($label) ? $label : $this->caption,
			'file' => $file,
			'line' => $line,
			'caller_file' => $caller_file,
			'caller_line' => $caller_line
		);
		//echo "<table border=1>$tm</table>";
		$this->addTimeMeasurement($tm);
		return $elapsed_time;
	}
	
	function addTimeMeasurement($tm){
		
		if (!isset($GLOBALS['TimeCalculator::TimeMeasurements'])) { $GLOBALS['TimeCalculator::TimeMeasurements'] = array(); };
		
		array_push($GLOBALS['TimeCalculator::TimeMeasurements'], $tm);	
		
	}

	function getAllTimeMeasurements(){
		return $GLOBALS['TimeCalculator::TimeMeasurements'];
	}
	
	
	function displayTable(){
	
		echo '<hr><table border=1 cellpadding=4 cellspacing=0>';
		
		foreach ($this->getAllTimeMeasurements() as $tm){
		
			$line = "<tr><td><b>{$tm['elapsed_time']}</b> </td><td> {$tm['caption']} </td><td> {$tm['file']}:{$tm['line']} </td><td> from {$tm['caller_file']}:{$tm['caller_line']} </td></tr>";
			echo "$line\n";
			}
		echo '</table>';
	}

	function displayTableBy_point(){
	
		echo '<hr><table border=1 cellpadding=4 cellspacing=0>';
		
		$res = array();
		foreach ($this->getAllTimeMeasurements() as $tm){
			if(isset($res["{$tm['file']}:{$tm['line']}"]))
			{
				$res["{$tm['file']}:{$tm['line']}"]['elapsed_time'] += $tm['elapsed_time'];
				$res["{$tm['file']}:{$tm['line']}"]['count']++;
			}
			else
			{
				$res["{$tm['file']}:{$tm['line']}"] = $tm;
				$res["{$tm['file']}:{$tm['line']}"]['count'] = 1;
			}
		}
		
		foreach ($res as $tm){
		
			$line = "<tr><td> {$tm['file']}:{$tm['line']} </td><td><b>{$tm['elapsed_time']}</b> </td><td><b>{$tm['count']}</b></td></tr>";
			echo "$line\n";
			}
		echo '</table>';
	}

	function getFloatTime($time_str) {
	    list($usec, $sec) = explode(" ", $time_str);
	    return ((float)$usec + (float)$sec);
	}

}

?>

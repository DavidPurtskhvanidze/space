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


namespace modules\miscellaneous\apps\FrontEnd\scripts;

class TaskSchedulerHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Settings';
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'task_scheduler';
	protected $rawOutput = true;

	private $logOffset = 1;
	private $lastRunTime;
	private $startTime;

    private $lastStartTimeStamp;
    private $lastEndTimeStamp;

    private $lastStartTime;
    private $lastEndTime;

    const ALLOWABLE_TIME_INTERVAL_PERFORMANCE = 1;//в ча�?ах


	public function respond()
	{
		if (isset(\App()->Request['showlog']))
		{
			echo "<pre>\n";
		}
		
		$this->init();

        if ($this->isRunning())
        {
            if (! $this->isCrashed())
            {
                $this->log('I can not run a duplicate. The process is started already in: ' . $this->lastEndTime);
                $this->log('Task Scheduler Finished');
                if (isset(\App()->Request['showlog']))
                {
                    echo "<pre>\n";
                }
                return false;
            }
            else
            {
                $this->log('Last start time: ' . \App()->SettingsFromDB->getSettingByName('task_scheduler_last_start_date'));
                $this->log('Last end time: ' . \App()->SettingsFromDB->getSettingByName('task_scheduler_last_end_date'));
                $this->log('Previous process probably was crashed. We continue to work');
            }

        }


        \App()->SettingsFromDB->updateSetting('task_scheduler_last_start_date', $this->getNow());
        $this->log('Task Scheduler Start Time ' . $this->getNow());
		
		$scheduledTasks = new \core\ExtensionPoint('modules\miscellaneous\lib\IScheduledTask');
		foreach ($scheduledTasks as $scheduledTask)
		{
			$scheduledTask->setTaskScheduler($this);
			$this->log('Running ' . get_class($scheduledTask));
			$this->logOffset += 1;
			$scheduledTask->run();
			$this->logOffset -= 1;
		}
        $this->log('Task Scheduler End Time ' . $this->getNow());

		$this->log('Task Scheduler Finished');

		\App()->SettingsFromDB->updateSetting('task_scheduler_last_end_date', $this->getNow());
		
		if (isset(\App()->Request['showlog'], \App()->Request['returnBackUri']))
		{
			echo "\n<a href='{$_REQUEST["returnBackUri"]}'>Go Back</a>";
		}

		if (isset($_REQUEST['showlog']))
		{
			echo "\n</pre>";
		}
	}

    private function isRunning()
    {
        return $this->lastStartTimeStamp > $this->lastEndTimeStamp;
    }

    private function isCrashed()
    {
        return ($this->lastStartTimeStamp + (self::ALLOWABLE_TIME_INTERVAL_PERFORMANCE * 60 * 60)) < time();
    }

	public function getStartTime()
	{
		return $this->startTime;
	}

	public function getLastRunTime()
	{
		return $this->lastRunTime;
	}

    private function getNow()
    {
        return date('Y-m-d H:i:s', time());
    }

	public function log($message)
	{
		$now = date('Y-m-d H:i:s.u');
		$offsetString = str_repeat("\t", $this->logOffset);
		$logString = $now . $offsetString . str_replace("\n", $offsetString . "\n", $message) . "\n";
		fwrite($this->logFileHandle, $logString);
		fflush($this->logFileHandle); // flush each time we write a line to the log
		if (isset($_REQUEST['showlog'])) echo "$logString";
	}

	private function init()
	{
		$this->lastStartTime = \App()->SettingsFromDB->getSettingByName('task_scheduler_last_start_date');
		$this->lastEndTime = \App()->SettingsFromDB->getSettingByName('task_scheduler_last_end_date');
        $this->lastStartTimeStamp = strtotime($this->lastStartTime);
        $this->lastEndTimeStamp = strtotime($this->lastEndTime);

        $this->lastRunTime = $this->lastEndTime;
        $this->startTime = $this->getNow();

		set_time_limit(0);
		$this->logFileHandle = fopen(PATH_TO_ROOT . \App()->SystemSettings['TaskSchedulerLogFilename'], 'a+');
		fwrite($this->logFileHandle, "\n");
		$this->log('Task Scheduler Started');
		$this->log('Last Executed Date of Task Scheduler: ' . $this->lastEndTime);
	}
	
	public function __destruct()
	{
		if (isset($this->logFileHandle))
			fclose($this->logFileHandle);
	}
}

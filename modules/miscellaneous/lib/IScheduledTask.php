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

/**
 * Scheduled task action interface.
 * 
 * Interface designed for performing action as Scheduled task
 * 
 * @category ExtensionPiont
 */
interface IScheduledTask
{
	/**
	 * Task scheduler handler setter
	 * @param modules\miscellaneous\apps\FrontEnd\scripts\TaskSchedulerHandler $taskSchedulerHandler
	 */
	public function setTaskScheduler($taskSchedulerHandler);
	/**
	 * Returns menu items group order.
	 * @return integer
	 */
	public static function getOrder();
	/**
	 * Scheduled task execution function
	 */
	public function run();
}

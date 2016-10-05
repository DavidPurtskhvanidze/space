<?php
/**
 *
 *    Module: export_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19780, 2016-06-17 13:19:18
 *
 *    This file is part of the 'export_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_users\apps\AdminPanel\scripts;

class ExportUsersFileHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'export_users';
	protected $functionName = 'export_users_file';
	protected $rawOutput = true;

	public function respond()
	{
		$userSids = \App()->Session->getContainer('EXPORT_USERS')->getValue('userSids');
		$properties = \App()->Session->getContainer('EXPORT_USERS')->getValue('properties');

		$exportUsersFactory = new \modules\export_users\lib\ExportUsersFactory();
		$exportUsers = $exportUsersFactory->createDataTransceiver($userSids, $properties);
		$exportUsers->perform();
		$exportUsers->finalize();
	}
}

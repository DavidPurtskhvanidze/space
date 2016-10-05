<?php
/**
 *
 *    Module: admin_dashboard v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: admin_dashboard-7.3.0-1
 *    Tag: tags/7.3.0-1@18504, 2015-08-24 13:35:28
 *
 *    This file is part of the 'admin_dashboard' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\admin_dashboard\apps\AdminPanel\scripts;

class DisplayDashboardHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'admin_dashboard';
	protected $functionName = 'display_dashboard';
    protected $isPermissionRequired = false;

	const NEWS_RSS_URL = "http://www.worksforweb.com/company/news/rss/";
	const PUBLICATIONS_RSS_URL = "http://www.worksforweb.com/publications/rss/";

	private $rssErrors = array();

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$versionReader = \App()->ObjectMother->createVersionReader();
		$newestVersion = $versionReader->getVersion();
		$currentVersion = \App()->SettingsFromDB->getSettingByName('product_version');
		$upToDate = version_compare($currentVersion, $newestVersion) >= 0;

		$templateProcessor->assign('newestVersion', $newestVersion);
		$templateProcessor->assign('upToDate', $upToDate);
		$templateProcessor->assign("freshStats", \App()->AdminDashboardManager->getFreshStatsProvidersGranted());
		$templateProcessor->assign("statBlocks", \App()->AdminDashboardManager->getStatBlocksGranted());
		$date = \App()->SettingsFromDB->getSettingByName('task_scheduler_last_end_date');
		$date = \App()->I18N->getDate($date);
		$templateProcessor->assign("taskSchedulerLastExecutedDate", $date);
		$templateProcessor->assign("taskSchedulerLogFilename", \App()->SystemSettings['TaskSchedulerLogFilename']);
		$templateProcessor->assign("frontEndUrl", \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl'));
		$templateProcessor->assign("news", $this->getDataFromRss(self::NEWS_RSS_URL));
		$templateProcessor->assign("publications", $this->getDataFromRss(self::PUBLICATIONS_RSS_URL));
		$templateProcessor->assign("errors", $this->rssErrors);
		$templateProcessor->assign("changelogUrl", \App()->SettingsFromDB->getSettingByName('changelog_url'));

		$templateProcessor->display('dashboard.tpl');
	}

	private function getDataFromRss($url)
	{
		$rssReaderWithCache = \App()->ObjectMother->createRssReaderWithCache();
		try
		{
			return $rssReaderWithCache->getItems($url, 3);
		}
		catch (\core\LibXmlErrorsException $e)
		{
			foreach ($e->getErrors() as $libXmlError)
			{
				\App()->ErrorMessages->addMessage('XML_ERROR', array('message' => $libXmlError->message));
			}
		}
		catch (\modules\miscellaneous\lib\InvalidRSSException $e)
		{
			\App()->ErrorMessages->addMessage('INVALID_RSS', array('filename' => $e->getFilename()));
		}
		return null;
	}
}

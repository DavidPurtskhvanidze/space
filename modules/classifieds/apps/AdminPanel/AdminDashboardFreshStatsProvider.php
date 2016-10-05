<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\AdminPanel;

class AdminDashboardFreshStatsProvider extends \modules\admin_dashboard\apps\AdminPanel\AbstractFreshStatsProvider
{
	private $templateProcessor;

	public function __construct()
	{
		$this->templateProcessor = \App()->getTemplateProcessor();
	}

	public function getCaption()
	{
		return 'New Listings';
	}

	public function getStatForLastDay()
	{
		return $this->getStatsForPeriod("-1 day", \App()->ListingManager->getListingsCountForLastDay());
	}

	public function getStatForLastWeek()
	{
		return $this->getStatsForPeriod("-7 days", \App()->ListingManager->getListingsCountForLastWeek());
	}

	public function getStatForLastMonth()
	{
		return $this->getStatsForPeriod("-1 month", \App()->ListingManager->getListingsCountForLastMonth());
	}

	private function getStatsForPeriod($period, $count)
	{
		$this->templateProcessor->assign('count', $count);
		$this->templateProcessor->assign('date', \App()->I18N->getDate(date('Y-m-d', strtotime($period))));
		return $this->templateProcessor->fetch('classifieds^admin_dashboard_fresh_stat.tpl');
	}
}

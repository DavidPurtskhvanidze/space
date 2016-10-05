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


namespace modules\admin_dashboard\apps\AdminPanel;

interface IFreshStatsProvider extends \ArrayAccess
{
	public function getCaption();
	public function getStatForLastDay();
	public function getStatForLastWeek();
	public function getStatForLastMonth();
}

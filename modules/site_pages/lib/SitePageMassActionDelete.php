<?php
/**
 *
 *    Module: site_pages v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: site_pages-7.5.0-1
 *    Tag: tags/7.5.0-1@19834, 2016-06-17 13:21:53
 *
 *    This file is part of the 'site_pages' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\site_pages\lib;

class SitePageMassActionDelete extends AbstractSitePageMassAction
{
	protected $id = 'delete';
	protected $caption = 'Delete';
	protected $applicationIds = array('AdminPanel', 'FrontEnd', 'MobileFrontEnd');

	public function perform($applicationId, $sitePages)
	{
		foreach ($sitePages as $sitePageUri)
		{
			\App()->PageManager->deletePage($sitePageUri, $applicationId);
		}
		if (count($sitePages) == 1)
		{
			\App()->SuccessMessages->addMessage('PAGE_DELETED');
		}
		elseif (count($sitePages) > 1)
		{
			\App()->SuccessMessages->addMessage('SELECTED_PAGES_DELETED');
		}
	}
}

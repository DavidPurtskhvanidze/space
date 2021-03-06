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


namespace modules\site_pages\apps\FrontEnd;

abstract class AbstractSitePageExtraField implements ISitePageExtraField
{
	protected $id;
	protected $caption;

	public function getId()
	{
		return $this->id;
	}

	public function getCaption()
	{
		return $this->caption;
	}
}

<?php
/**
 *
 *    Module: business_catalog v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: business_catalog-7.5.0-1
 *    Tag: tags/7.5.0-1@19772, 2016-06-17 13:18:58
 *
 *    This file is part of the 'business_catalog' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\business_catalog\apps\SubDomain\scripts;

class ShowBusinessCatalogHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Display Business Catalog';
	protected $moduleName = 'business_catalog';
	protected $functionName = 'show_business_catalog';
	protected $parameters = array('category_id', 'record_id');

	public function respond()
	{
		$handler = new \modules\business_catalog\apps\FrontEnd\scripts\ShowBusinessCatalogHandler();
		$handler->respond();
	}
}

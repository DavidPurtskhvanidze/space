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


namespace modules\business_catalog\apps\FrontEnd\scripts;

class ShowBusinessCatalogHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Business Catalog';
	protected $moduleName = 'business_catalog';
	protected $functionName = 'show_business_catalog';
	protected $parameters = array('category_id', 'record_id');

	public function respond()
	{
		$businessCatalog = new \modules\business_catalog\lib\BusinessCatalog();
		$template_processor = \App()->getTemplateProcessor();

		$cathegory_exists = false;
		$record_exists = false;
		$categories = array ();

		if( isset($_REQUEST['category_id']) && $businessCatalog->bcategory_exists($_REQUEST['category_id']) )
		{
			$cathegory_exists = true;
			if(isset($_REQUEST['record_id'],$_REQUEST['category_id']) && $businessCatalog->brecord_exists($_REQUEST['category_id'],$_REQUEST['record_id']))
			{
				$cur_cathegory = $businessCatalog->bget_record($_REQUEST['record_id']);
				$record_exists = true;
			}
			else
			{
				$categories = $businessCatalog->bget_records($_REQUEST['category_id']);
			}
		}
		else
		{
			$categories = $businessCatalog->bget_categories_www();
		}

			if (isset ($_REQUEST['category_id']))
				$template_processor->assign("current_category", $cathegory_exists ? $_REQUEST['category_id'] : null);
			if ($record_exists)
				$template_processor->assign("current_company", $record_exists ? $cur_cathegory : null);
			$template_processor->assign("records", $categories);
			$template_processor->display ("business_catalog.tpl");
	}
}

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


namespace modules\classifieds\lib\Category;

class CategoryDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'classifieds_categories';
	
	protected $detailsInfo = array(
		
				array
				(
					'id'		=> 'id',
					'caption'	=> 'ID', 
					'type'		=> 'dashed_unique_string',
					'length'	=> '20',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> true,
					'is_system'	=> true,
				),
				array
				(
					'id'		=> 'name',
					'caption'	=> 'Name', 
					'type'		=> 'string',
					'length'	=> '20',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> true,
					'is_system'	=> false,
				),
				array
				(
					'id'		=> 'parent',
					'caption'	=> '',
					'type'		=> 'hidden',
					'length'	=> '20',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> true,
				),
				array
				(
					'id'		=> 'input_template',
					'caption'	=> 'Input Form Template', 
					'type'		=> 'string',
					'length'	=> '255',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
				),
				array
				(
					'id'		=> 'search_template',
					'caption'	=> 'Search Form Template', 
					'type'		=> 'string',
					'length'	=> '255',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
				),
				array
				(
					'id'		=> 'refine_search_template',
					'caption'	=> 'Refine Search Template', 
					'type'		=> 'string',
					'length'	=> '255',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
				),
				array
				(
					'id'		=> 'search_result_template',
					'caption'	=> 'Search Results Template', 
					'type'		=> 'string',
					'length'	=> '255',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
				),
				array
				(
					'id'		=> 'view_template',
					'caption'	=> 'Template for View Listing', 
					'type'		=> 'string',
					'length'	=> '255',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
				),
				array
				(
					'id'		=> 'listing_caption_template_content',
					'caption'	=> 'Listing Caption', 
					'type'		=> 'string',
					'length'	=> '1024',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
				),
				array
				(
					'id'		=> 'listing_url_seo_data',
					'caption'	=> 'SEO Data Included in Listing URL',
					'type'		=> 'string',
					'length'	=> '255',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
				),
				array
				(
					'id'		=> 'meta_keywords',
					'caption'	=> 'Meta Keywords',
					'type'		=> 'text',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
					'input_template' => 'textarea.tpl'
				),
				array
				(
					'id'		=> 'meta_description',
					'caption'	=> 'Meta Description',
					'type'		=> 'text',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
					'input_template' => 'textarea.tpl'
				),
				array
				(
					'id'		=> 'page_title',
					'caption'	=> 'Page Title',
					'type'		=> 'text',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
					'input_template' => 'textarea.tpl'
				),
				array
				(
					'id'		=> 'browsing_settings',
					'caption'	=> '',
					'type'		=> 'array',
                    'table_name'=> 'classifieds_categories',
					'is_required'=> false,
					'is_system'	=> false,
				),

		);			
}

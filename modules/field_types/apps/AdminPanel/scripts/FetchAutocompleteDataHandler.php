<?php
/**
 *
 *    Module: field_types v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: field_types-7.5.0-1
 *    Tag: tags/7.5.0-1@19782, 2016-06-17 13:19:23
 *
 *    This file is part of the 'field_types' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\field_types\apps\AdminPanel\scripts;

class FetchAutocompleteDataHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $displayName = 'Fetch Autocomplete Data';
	protected $moduleName = 'field_types';
	protected $functionName = 'fetch_autocomplete_data';
	protected $rawOutput = true;

	public function respond()
	{
		$fieldTypeActionFactory = new \modules\field_types\lib\Actions\FieldTypeActionFactory();
		$action = $fieldTypeActionFactory->createFetchAutocompleteDataAction();
		
		$jsonData = $action->perform();

		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		echo json_encode($jsonData);
	}
}

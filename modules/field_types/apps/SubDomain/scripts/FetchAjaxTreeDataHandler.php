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


namespace modules\field_types\apps\SubDomain\scripts;

class FetchAjaxTreeDataHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Fetch Ajax Tree Data';
	protected $moduleName = 'field_types';
	protected $functionName = 'fetch_ajax_tree_data';
	protected $rawOutput = true;

	public function respond()
	{
        if (\App()->Request['object'] == 'classifieds')
        {
            $jsonData = \App()->ListingFieldManager->getTreeValuesByParentSID(\App()->Request['field_sid'], \App()->Request['parent_sid']);
        }
        else if (\App()->Request['object'] == 'users')
        {
            $jsonData = \App()->UserProfileFieldManager->getTreeValuesByParentSID(\App()->Request['field_sid'], \App()->Request['parent_sid']);
        }

        $templateProcessor = App()->getTemplateProcessor();
        foreach($jsonData as &$value)
        {
            $value = $templateProcessor->fetch("string:[[{$value}]]");
        }

		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		echo json_encode($jsonData);
	}
}

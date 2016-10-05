<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\AdminPanel\scripts;

class GeographicDataActionsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'geographic_data_actions';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();

		$location = \App()->LocationManager->createLocation($_REQUEST);
		$location->deleteProperty('sid');
		$add_form = \App()->ObjectMother->createForm($location);
		$add_form->registerTags($template_processor);
		$form_is_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add');

		if ($form_is_submitted && $add_form->isDataValid())
		{
			\App()->LocationManager->saveLocation($location);
			\App()->SuccessMessages->addMessage('LOCATION_SAVED');
		}
		elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
		{
			$location_sid = isset($_REQUEST['location_sid']) ? $_REQUEST['location_sid'] : null;

			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\miscellaneous\apps\AdminPanel\IDeleteGeographicDataValidator');
			foreach ($validators as $validator)
			{
				$validator->setLocationSid($location_sid);
				$canPerform &= $validator->isValid();
			}
			if ($canPerform)
			{
				$locationInfo = \App()->LocationManager->getLocationInfoBySID($location_sid);
				\App()->ListingManager->onDeleteLocation($locationInfo['name']);
				\App()->LocationManager->deleteLocationBySID($location_sid);
			}
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPageURLById('geographic_data'));
		}
		elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'clear_data')
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\miscellaneous\apps\AdminPanel\IDeleteAllGeographicDataValidator');
			foreach ($validators as $validator)
			{
				$canPerform &= $validator->isValid();
			}
			if ($canPerform)
			{
				\App()->LocationManager->deleteAllLocations();
			}
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPageURLById('geographic_data'));
		}

		$template_processor->assign("form_fields", $add_form->getFormFieldsInfo());
		$template_processor->display("add_geographic_data.tpl");
	}
}

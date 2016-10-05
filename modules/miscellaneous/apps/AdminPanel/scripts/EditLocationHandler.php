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

// version 5 wrapper header

class EditLocationHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'edit_location';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$location_sid = \App()->Request->getValueOrDefault('sid');

		$location_info = \App()->LocationManager->getLocationInfoBySid($location_sid);

		if (!is_null($location_info))
		{
			$location_info = array_merge($location_info, $_REQUEST);
			$location = \App()->LocationManager->createLocation($location_info);
			array_map(array($location,'deleteProperty'),array('sid'));

			$editLocation_form = new \lib\Forms\Form($location);
			$form_submitted = (\App()->Request->getValueOrDefault('action') == 'save_info');

			if ($form_submitted && $editLocation_form->isDataValid())
			{
				\App()->LocationManager->saveLocation($location);
				throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . "/geographic_data/");
			}
			else
			{
				$editLocation_form->registerTags($template_processor);
				$template_processor->assign("location", \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($location));
				$template_processor->assign("location_sid", $location_sid);
				$template_processor->assign("form_fields", $editLocation_form->getFormFieldsInfo());
				$template_processor->display("edit_location.tpl");
			}
		}
	}
}

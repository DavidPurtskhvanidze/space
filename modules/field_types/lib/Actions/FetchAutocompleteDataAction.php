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


namespace modules\field_types\lib\Actions;

class FetchAutocompleteDataAction
{
	public function perform()
	{
		$propertyData = \App()->Request->getValueOrDefault('property_data', 'none');
		$serviceName = \App()->Request['autocomplete_service_name'];
		$methodName = \App()->Request['autocomplete_method_name'];
		
		$preselectFieldNames = \App()->Request->getValueOrDefault('preselection_fields', array());
		$formFieldValues = \App()->Request->getValueOrDefault('form_field_values', '');
		parse_str($formFieldValues, $formFieldValues);
		
		$keyword = \App()->Request->getValueOrDefault('keyword', null);
		$maxItems = \App()->Request->getValueOrDefault('max_items', 10);

		$autocompleteData = array(
			'completed' => false,
			'message' => '',
			'options' => array()
		);
		
		try
		{
			if ($serviceName && $methodName)
			{
				$autocompleteData['options'] = \App()->{$serviceName}->{'fetchAutocompleteOptionsFor' . $methodName}($keyword, $maxItems, $formFieldValues, $preselectFieldNames);
			}
			else
			{
				$propertyData = \App()->AutocompleteManager->unpackPropertyForRequest($propertyData);
				$autocompleteData['options'] = \App()->AutocompleteManager->fetchAutocompleteOptions($propertyData, $keyword, $maxItems);
			}
			
			$autocompleteData['completed'] = true;
			$autocompleteData['message'] = 'Requested data fetch succesfully completed';
		}
		catch(\Exception $e)
		{
			$autocompleteData['completed'] = false;
			$autocompleteData['message'] = (string) $e;
		}
		
		return $autocompleteData;
	}
}

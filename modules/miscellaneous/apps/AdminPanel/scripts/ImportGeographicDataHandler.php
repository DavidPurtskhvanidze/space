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

class ImportGeographicDataHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'import_geographic_data';

	private $errors = null;
	
	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();

		$this->errors = false;

		$start_line			= isset($_REQUEST['start_line']) ? $_REQUEST['start_line'] : null;
		$name_column		= isset($_REQUEST['name_column']) ? $_REQUEST['name_column'] : null;
		$longitude_column	= isset($_REQUEST['longitude_column']) ? $_REQUEST['longitude_column'] : null;
		$latitude_column	= isset($_REQUEST['latitude_column']) ? $_REQUEST['latitude_column'] : null;
		$file_format		= isset($_REQUEST['file_format']) ? $_REQUEST['file_format'] : null;
		$fields_delimiter	= isset($_REQUEST['fields_delimiter']) ? $_REQUEST['fields_delimiter'] : null;
		$updateOnMatch		= isset($_REQUEST['update_on_match']) ? $_REQUEST['update_on_match'] : null;


		$imported_file_config['start_line'] = $start_line;
		$imported_file_config['name_column'] = $name_column;
		$imported_file_config['longitude_column'] = $longitude_column;
		$imported_file_config['latitude_column'] = $latitude_column;
		$imported_file_config['file_format'] = $file_format;
		$imported_file_config['fields_delimiter'] = $fields_delimiter;
		$imported_file_config['update_on_match'] = $updateOnMatch;

		$imported_location_count = null;
		$updatedLocationsCount = null;

		if (isset($_FILES['imported_geo_file']) && !$_FILES['imported_geo_file']['error']) {

			if (empty($_FILES['imported_geo_file']['name']))
			{
				$this->addErrorMessage('File','EMPTY_VALUE');
			}

			if (empty($start_line))
			{
				$this->addErrorMessage('Start Line','EMPTY_VALUE');
			}
			elseif (!is_numeric($start_line) || !is_int($start_line + 0))
			{
				$this->addErrorMessage('Start Line','NOT_INTEGER_VALUE');
			}

			if (empty($name_column))
			{
				$this->addErrorMessage('Name Column','EMPTY_VALUE');
			}
			elseif (!is_numeric($name_column) || !is_int($name_column + 0))
			{
				$this->addErrorMessage('Name Column','NOT_INTEGER_VALUE');
			}

			if (empty($longitude_column))
			{
				$this->addErrorMessage('Longitude Column','EMPTY_VALUE');
			}
			elseif (!is_numeric($longitude_column) || !is_int($longitude_column + 0))
			{
				$this->addErrorMessage('Longitude Column','NOT_INTEGER_VALUE');
			}

			if (empty($latitude_column))
			{
				$this->addErrorMessage('Latitude Column','EMPTY_VALUE');
			}
			elseif (!is_numeric($latitude_column) || !is_int($latitude_column + 0))
			{
				$this->addErrorMessage('Latitude Column','NOT_INTEGER_VALUE');
			}

			if (!$this->errors)
			{

				set_time_limit(0);

				if (!strcasecmp($file_format, 'excel')) {

					$imported_file = new \modules\miscellaneous\lib\ImportedExcelFile();

					$imported_file->setParseDate(false);

				} else {

					$imported_file = new \modules\miscellaneous\lib\ImportedCSVFile();

					if ($fields_delimiter == "semicolumn") {

						$fields_delimiter = ";";

					} elseif ($fields_delimiter == "tab") {

						$fields_delimiter = "\t";

					} else {

						$fields_delimiter = ",";

					}

					$imported_file->setFieldsDelimiter($fields_delimiter);
				}

				$imported_file->setFileName($_FILES['imported_geo_file']['tmp_name']);

				$imported_data = $imported_file->getTable();

				$imported_location_count = 0;
				$updatedLocationsCount = 0;

				$locationsInfo = \App()->LocationManager->getLocationsInfo();
				$locationsNames = array_map(function($locationInfo) {return $locationInfo['name'];}, $locationsInfo);

				for ($i = $start_line - 1; $i < count($imported_data); $i++) {

					if (!isset($imported_data[$i][$name_column - 1], $imported_data[$i][$longitude_column - 1], $imported_data[$i][$latitude_column - 1]))	continue;

					$name = $imported_data[$i][$name_column - 1];

					$longitude = $imported_data[$i][$longitude_column - 1];

					$latitude = $imported_data[$i][$latitude_column - 1];

					if (!in_array($name, $locationsNames))
					{
						\App()->LocationManager->addLocation($name, $longitude, $latitude);
						$imported_location_count++;
						$locationsNames[] = $name;
					}
					elseif ($updateOnMatch)
					{
						\App()->LocationManager->updateLocation($name, $longitude, $latitude);
						$updatedLocationsCount++;
					}

				}
				$flagOfChanges = true;
				if($imported_location_count > 0)
				{
					\App()->SuccessMessages->addMessage('LOCATIONS_ADDED',array('count' => $imported_location_count));
					$flagOfChanges = false;
				}
				if($updatedLocationsCount > 0)
				{
					\App()->SuccessMessages->addMessage('LOCATIONS_UPDATED',array('count' => $updatedLocationsCount));
					$flagOfChanges = false;
				}
				if ($flagOfChanges)
				{
					\App()->SuccessMessages->addMessage('LOCATIONS_NO_CHANGES');
				}

			}

		}
		elseif ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			\App()->ErrorMessages->addMessage('FILE_NOT_UPLOADED',array('fieldCaption' => 'File'));			
		}

		$template_processor->assign("imported_location_count", $imported_location_count);
		$template_processor->assign("updated_location_count", $updatedLocationsCount);
		$template_processor->assign("imported_file_config", $imported_file_config);
		$template_processor->display("import_geographic_data_form.tpl");
	}
	
	function addErrorMessage($field,$type)
	{
		\App()->ErrorMessages->addMessage($type,array('fieldCaption' => $field));
		$this->errors = true;
	}
}

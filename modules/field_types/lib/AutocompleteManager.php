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


namespace modules\field_types\lib;

class AutocompleteManager implements \core\IService
{
	/**
	 * Packs Property For Request
	 * @param \lib\ORM\ObjectProperty $property
	 * @return string Packed and encrypted property
	 */
	public function packPropertyForRequest($property)
	{
		$propertyData = array(
			'table_name' => $property->getTableName(),
			'column_name' => $property->getColumnName(),
		);
		
		return $this->getCryptographer()->encrypt(serialize($propertyData));
	}
	/**
	 * Decrypts property data
	 * @param string $propertyData
	 * @return array
	 */
	public function unpackPropertyForRequest($propertyData)
	{
		$propertyData = $this->getCryptographer()->decrypt($propertyData);
		
		return unserialize($propertyData);
	}
	/**
	 * FetchAutocompleteOptions
	 * @param array $propertyData
	 * @param string $keyword
	 * @param int $maxRows
	 * @return array
	 * @throws Exception
	 */
	public function fetchAutocompleteOptions($propertyData, $keyword, $maxRows)
	{
		if (!isset($propertyData['table_name']) || empty($propertyData['table_name']) || !\App()->DB->table_exists($propertyData['table_name']))
		{
			throw new Exception('Invalid paramether 01 supllied');
		}
		$tableName = $propertyData['table_name'];
		$columnName = $propertyData['column_name'];
		
		$keyword = "%{$keyword}%";
		$dataSet = \App()->DB->query("SELECT DISTINCT `{$columnName}` AS `value` FROM `{$tableName}` WHERE(`{$columnName}` LIKE ?s) ORDER BY `{$columnName}` ASC LIMIT ?n", $keyword, $maxRows);
		if (empty($dataSet))
		{
			$dataSet = array();
		}
		
		$result = array();
		foreach($dataSet as $record)
		{
			$result[] = array(
				'value' => $record['value'],
				'label' => $record['value'],
			);
		}
		
		return $result;
	}
	
	private function getCryptographer()
	{
		static $cryptographer = null;
		
		if ($cryptographer === null)
		{
			$cryptographerFactory = new \modules\miscellaneous\lib\CryptographerFactory();
			$cryptographer = $cryptographerFactory->createCrypt();
		}
		
		return $cryptographer;
	}
}

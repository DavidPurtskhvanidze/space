<?php
/**
 *
 *    Module: export_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19779, 2016-06-17 13:19:16
 *
 *    This file is part of the 'export_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_listings\lib;

class ExportedListingTreeFieldsHandler
{
	private $fieldsId;

	public function setFieldsId($fieldsId)
	{
		$this->fieldsId = $fieldsId;
	}
	
	public function handle($exportedListing)
	{
		$data = $exportedListing->getData();
		$dataKeys = array_keys($data);
		$dataValues = array_values($data);
		foreach (array_intersect($this->fieldsId, $dataKeys) as $fieldId)
		{
			$pos = array_search($fieldId, $dataKeys);
			$treeValues = array_values($data[$fieldId]->getValue());
			$treeValues += array_fill(0, $data[$fieldId]->getTreeDepth(), null);
			array_splice($dataValues, $pos, 1, $treeValues);
			$treeKeys = array();
			for ($i = 1; $i <= $data[$fieldId]->getTreeDepth(); $i++)
			{
				$treeKeys[] = $fieldId . "[$i]";
			}
			array_splice($dataKeys, $pos, 1, $treeKeys);
			
		}
		$exportedListing->setData(array_combine($dataKeys, $dataValues));
	}
}

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


namespace modules\classifieds\lib\Listing;

class AdditionalListingOptionManager implements \core\IService
{
	/**
	 * List of additional options
	 * @var array
	 */
	private $options;
	
	public function init()
	{
		$options = new \core\ExtensionPoint('modules\classifieds\IAdditionalListingOption');
		$this->options = array();
		foreach ($options as $option)
		{
			$this->options[$option->getId()] = $option;
		}
	}
	/**
	 * Returns options availabe for given listing
	 * @param Listing $listing
	 * @return array Array of options
	 */
	public function getOptionsByListing($listing)
	{
		$result = array();
		foreach ($this->options as $option)
		{
			$option->setListing($listing);
			if ($option->isAvailable())
			{
				$result[$option->getId()] = $option;
			}
		}
		
		return $result;
	}
	/**
	 * Returns array of array with key - value of need data in for template rendering
	 * @param Listing $listing
	 * @return array Array of option template structure
	 */
	public function getOptionTemplateStructureByListing($listing)
	{
		$options = $this->getOptionsByListing($listing);
		$result = array();
		foreach ($options as $option)
		{
			$result[$option->getId()] = array(
				'id' => $option->getId(),
				'caption' => $option->getCaption(),
				'description' => $option->getDescription(),
				'additional_script' => $option->getAdditionalScript()
			);
		}
		return $result;
	}
	/**
	 * Returns free additional options
	 * @param Listing $listing
	 * @return array Array of free additional options 
	 */
	public function getFreeOptionIdsByListing($listing)
	{
		$options = $this->getOptionsByListing($listing);
		$result = array();
		foreach ($options as $option)
		{
			if (!((bool) $option->getPrice()))
			{
				$result[] = $option->getId();
			}
		}
		return $result;
	}
	/**
	 * Returns free additional option ids
	 * @param Listing $listing
	 * @return array Array of paid additional option ids
	 */
	public function getPaidOptionIdsByListing($listing)
	{
		return array_keys($this->getPaidOptionsByListing($listing));
	}
	/**
	 * Returns free additional options
	 * @param Listing $listing
	 * @return array Array of paid additional options 
	 */
	public function getPaidOptionsByListing($listing)
	{
		$options = $this->getOptionsByListing($listing);
		$result = array();
		foreach ($options as $option)
		{
			$price = $option->getPrice();
			if ((bool) $price)
			{
				$result[$option->getId()] = array
				(
					'id' => $option->getId(),
					'name' => $option->getCaption(),
					'caption' => $option->getCaption(),
					'price' => $price
				);
			}
		}
		return $result;
	}
	/**
	 * Activates option
	 * @param Listing $listing
	 * @param String $optionId
	 */
	public function activateOptionForListing($listing, $optionId)
	{
		$options = $this->getOptionsByListing($listing);
		if (isset($options[$optionId]))
		{
			$options[$optionId]->activateOption();
		}
	}
	
	public function isListingOptionWithIdExist($listing, $optionId)
	{
		$options = $this->getOptionsByListing($listing);
		return isset($options[$optionId]);
	}
}

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


namespace modules\classifieds\apps\FrontEnd\scripts;

class ManageListingOptionsHandlerLogger
{
	private $optionsAddedToContainer = array();
	private $optionsAddedToBasket = array();
	private $optionsActivated = array();

	public function getOptionsActivated()
	{
		return $this->optionsActivated;
	}

	public function getOptionsAddedToBasket()
	{
		return $this->optionsAddedToBasket;
	}

	public function getOptionsAddedToContainer()
	{
		return $this->optionsAddedToContainer;
	}

	public function logListingActivationAddedToBasket()
	{
		$this->logOptionsAddedToBasket(array("activation"));
	}

	public function logOptionsAddedToContainer($optionIds)
	{
		$this->optionsAddedToContainer = array_merge($this->optionsAddedToContainer, $optionIds);
	}

	public function logOptionsAddedToBasket($optionIds)
	{
		$this->optionsAddedToBasket = array_merge($this->optionsAddedToBasket, $optionIds);
	}

	public function logOptionsActivated($optionIds)
	{
		$this->optionsActivated = array_merge($this->optionsActivated, $optionIds);
	}
	
	public function logListingActivated()
	{
		$this->optionsActivated[] = 'activation';
	}
}

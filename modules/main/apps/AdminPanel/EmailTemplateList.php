<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\main\apps\AdminPanel;

abstract class EmailTemplateList implements IEmailTemplatesList
{
	protected $id;
	protected $caption;
	protected $availableVariables;

	public function __construct()
	{
		$this->availableVariables[] = '$GLOBALS';
	}

	public function getId()
	{
		return $this->id;
	}

	public function getCaption()
	{
		return $this->caption;
	}

	/**
	 * @return mixed
	 */
	public function getAvailableVariables()
	{
		return $this->availableVariables;
	}
}

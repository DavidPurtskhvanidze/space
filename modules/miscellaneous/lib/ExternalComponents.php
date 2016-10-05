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


namespace modules\miscellaneous\lib;

use core\IService;

class ExternalComponents implements IService
{
	private $components = array();

	public function requireComponent($component, $file, $type = null)
	{
		$componentData =
            [
                'component' => $component,
                'file' => $file,
                'type' => $type ? $type : pathinfo($file, PATHINFO_EXTENSION)
            ];

		if (!in_array($componentData, $this->components))
		{
			$this->components[] = $componentData;
		}
	}

	public function getRequiredComponents()
	{
		return $this->components;
	}
}

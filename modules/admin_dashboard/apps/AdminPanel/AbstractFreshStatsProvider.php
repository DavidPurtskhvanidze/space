<?php
/**
 *
 *    Module: admin_dashboard v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: admin_dashboard-7.3.0-1
 *    Tag: tags/7.3.0-1@18504, 2015-08-24 13:35:28
 *
 *    This file is part of the 'admin_dashboard' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\admin_dashboard\apps\AdminPanel;

abstract class AbstractFreshStatsProvider implements IFreshStatsProvider
{
	public function offsetGet($index)
	{
		switch($index)
		{
			case 'caption' : return $this->getCaption();
			case 'forLastDay' : return $this->getStatForLastDay();
			case 'forLastWeek' : return $this->getStatForLastWeek();
			case 'forLastMonth' : return $this->getStatForLastMonth();
			default: throw new \Exception("Unknown key \"$index\" requested");
		}
	}

	public function offsetExists($index){return false;}
	public function offsetSet($offset, $value){throw new \Exception("Listing stats is a read-only object");}
	public function offsetUnset($offset){throw new \Exception("Listing stats is a read-only object");}
}

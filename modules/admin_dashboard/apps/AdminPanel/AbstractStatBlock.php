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

abstract class AbstractStatBlock implements IStatBlock
{
	protected $templateProcessor;

	public function __construct()
	{
		$this->templateProcessor = \App()->getTemplateProcessor();
	}

	abstract public function getIStatItemInterfaceName();

	public function offsetGet($index)
	{
		switch($index)
		{
			case 'divClass' : return $this->getDivClass();
			case 'caption' : return $this->getCaption();
			case 'content' : return $this->getContent();
			default: throw new \Exception("Unknown key \"$index\" requested");
		}
	}

	public function offsetExists($index){return false;}
	public function offsetSet($offset, $value){throw new \Exception("Stats block is a read-only object");}
	public function offsetUnset($offset){throw new \Exception("Stats block is a read-only object");}

	protected function getContent()
	{
		$statItems = new \core\ExtensionPoint($this->getIStatItemInterfaceName());
		$this->templateProcessor->assign('statItems', $statItems);
		return $this->templateProcessor->fetch('stat_block.tpl');
	}
}

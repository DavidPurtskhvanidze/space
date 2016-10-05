<?php
/**
 *
 *    Module: menu v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: menu-7.5.0-1
 *    Tag: tags/7.5.0-1@19799, 2016-06-17 13:20:07
 *
 *    This file is part of the 'menu' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\menu\apps\FrontEnd;

abstract class MenuItem implements \modules\menu\apps\FrontEnd\IMenuItem
{
	protected $params;
	
	public static function getOrder()
	{
		return 100;
	}
	/**
	 * Fetch
	 * @param array $params
	 * @param \modules\smarty_based_template_processor\lib\TemplateProcessor $templateProcessor
	 */
	public function fetch($params, $templateProcessor)
	{
		// Initializing
		$this->params = $params;
		$template = $templateProcessor->createTemplate($this->getTemplate());

		// Method body
		$template->assign('wrapperStartTag', $this->getParamValue('wrapperStartTag', '<li>'));
		$template->assign('wrapperEndTag', $this->getParamValue('wrapperEndTag', '</li>'));
		$template->assign('caption', $this->getCaption());
		$template->assign('title', $this->getTitle());
		$template->assign('url', $this->getUrl());
		$template->assign('params', $this->params);

		return $template->fetch();
	}

	protected function getTemplate()
	{
		return $this->getParamValue('template', 'menu_item.tpl');
	}

	protected function getParamValue($paramrName, $defaultValue = null)
	{
		return isset($this->params[$paramrName]) ? $this->params[$paramrName] : $defaultValue;
	}
}

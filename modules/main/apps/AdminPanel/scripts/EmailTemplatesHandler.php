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


namespace modules\main\apps\AdminPanel\scripts;

class EmailTemplatesHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\miscellaneous\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'main';
	protected $functionName = 'email_templates';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$emailTemplates = array();

		$emailTemplatesList = new \core\ExtensionPoint('modules\main\apps\AdminPanel\IEmailTemplatesList');
		foreach($emailTemplatesList as $emailTemplate)
		{
			$template = array('id' => $emailTemplate->getId(), 'caption' => $emailTemplate->getCaption());
			array_push($emailTemplates, $template);
		}

		$templateProcessor->assign('sortingField', $this->sort($emailTemplates));
		$templateProcessor->assign('emailTemplates', $emailTemplates);
		$templateProcessor->display ("email_templates.tpl");
	}

	private function sort(&$emailTemplates)
	{
		$field = \App()->Request->getValueOrDefault('sorting_fields', array('id' => 'ASC'));

		usort($emailTemplates, function ($a, $b) use ($field)
		{
			return ($field[key($field)] == 'ASC')
				? strcmp($a[key($field)], $b[key($field)])
				: strcmp($b[key($field)], $a[key($field)]);
		});
		return array(key($field) => $field[key($field)] == 'ASC' ? 'DESC' : 'ASC');
	}

	public function getCaption()
	{
		return "Email Templates";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array();
	}

	public static function getOrder()
	{
		return 200;
	}
}

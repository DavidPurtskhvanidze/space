<?php
/**
 *
 *    Module: form_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: form_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19783, 2016-06-17 13:19:26
 *
 *    This file is part of the 'form_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */



namespace modules\form_manager\apps\AdminPanel\scripts;
 
class ManageFormsHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\site_pages\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'form_manager';
	protected $functionName = 'manage_forms';

	private $appId = 'FrontEnd';

	public function respond()
	{
		$this->appId = \App()->Request->getValueOrDefault('application_id', 'FrontEnd');
		if (!\App()->doesAppExist($this->appId))
		{
			throw new \lib\Http\NotFoundException('Requested application "' . $this->appId . '" does not exist');
		}

		if(\App()->Request->getValueOrDefault('action') == 'delete')
			$this->deleteForm(\App()->Request->getValueOrDefault('sid'));
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('forms', $this->getFormsInfo());
		$templateProcessor->assign('application_id', $this->appId);
		$templateProcessor->display("manage_forms.tpl");
	}
	private function deleteForm($sid)
	{
		if($sid)
		{
			\App()->FormManager->deleteFormBySID($sid);
            \App()->SuccessMessages->addMessage('FORM_DELETED');
		}
	}

	private function getFormsInfo()
	{
		$forms_info = \App()->FormManager->getFormsInfo($this->appId);
		$categories_info = \App()->CategoryManager->getAllCategoriesInfo();
		foreach ($forms_info as $form_key => $form) 
			foreach ($categories_info as $category) 
				if($category['sid'] == $form['category_sid']) 
					$forms_info[$form_key]['category_sid'] = $category['id'];
		return $forms_info;
	}
	public function getCaption()
	{
		return "Manage Forms";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array(\App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName));
	}

	public static function getOrder()
	{
		return 900;
	}
}

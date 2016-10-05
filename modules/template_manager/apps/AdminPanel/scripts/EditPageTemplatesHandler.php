<?php
/**
 *
 *    Module: template_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: template_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19839, 2016-06-17 13:22:09
 *
 *    This file is part of the 'template_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\template_manager\apps\AdminPanel\scripts;

class EditPageTemplatesHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\site_pages\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'template_manager';
	protected $functionName = 'edit_page_templates';

	public function respond()
	{
		$appId = \App()->Request->getValueOrDefault('application_id', 'FrontEnd');
		if (!\App()->doesAppExist($appId))
		{
			throw new \lib\Http\NotFoundException('Requested application "' . $appId . '" does not exist');
		}

		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign("appId", $appId);

		$moduleTemplateManager = \App()->TemplateManagerFactory->createModuleTemplateManager($appId, \App()->SystemSettings['PageTemplatesModuleName']);

		$templateName = \App()->Request['template'];
		$action = \App()->Request['action'];

		if ($action === "save")
		{
			$moduleTemplateManager->saveTemplate($templateName, \App()->Request['template_content']);
            $error = $moduleTemplateManager->getError($templateName);
            if (!isset($error['is_editable']))
                \App()->SuccessMessages->addMessage('CHANGES_HAVE_BEEN_SUCCESSFULLY_SAVED');

			$this->displayForm($templateName, $moduleTemplateManager, $templateProcessor, $appId);
		}
		elseif ($action === "edit")
		{
			$this->displayForm($templateName, $moduleTemplateManager, $templateProcessor, $appId);
		}
		elseif ($action == "view")
		{
			$this->displayForm($templateName, $moduleTemplateManager, $templateProcessor, $appId);
		}
		elseif ($action == "delete")
		{
			$moduleTemplateManager->deleteTemplate($templateName);
			$this->displayList($moduleTemplateManager, $templateProcessor);
		}
        elseif ($action == "create")
        {
            if (substr($templateName, -4, 4) != '.tpl')
                $templateName .= '.tpl';
            $moduleTemplateManager->createTemplate($templateName , "");
            $this->displayList($moduleTemplateManager, $templateProcessor);
        }
		else
		{
			$this->displayList($moduleTemplateManager, $templateProcessor);
		}
	}

	private function displayList($moduleTemplateManager, $templateProcessor)
	{
		$error = $moduleTemplateManager->getError();
        $templateIsEditable = (isset($error['isEditable'])and($error['isEditable']==false)) ? false : true;
		$templateProcessor->assign("moduleIsEditable", $templateIsEditable);
		$templateProcessor->assign("isNotEditableReason", $error);

		$templateProcessor->assign("templatesList", $moduleTemplateManager->getTemplatesList());
		$templateProcessor->display("page_templates_list.tpl");
	}

	private function displayForm($templateName, $moduleTemplateManager, $templateProcessor, $appId)
    {
        $error = $moduleTemplateManager->getError($templateName);
        $templateIsEditable = (isset($error['isEditable'])and($error['isEditable']==false)) ? false : true;
        $templateProcessor->assign("templateIsEditable", $templateIsEditable);
        $templateProcessor->assign("isNotEditableReason", $error);

        $templateContent = $moduleTemplateManager->getTemplateContent($templateName);
	    $colorizeManager = new \modules\template_manager\lib\ColorizeManager($templateName);

		$templateProcessor->assign("templateContent", $templateContent);
		$templateProcessor->assign("templateName", $templateName);
		$templateProcessor->assign("currentThemeName", $moduleTemplateManager->getThemeManager()->getCurrentThemeName());
		$templateProcessor->assign('modulesAndFunctionsData', \App()->PageManager->getModuleFunctionParamList($appId));
		$templateProcessor->assign('themeColors', $colorizeManager->getRules());
		$templateProcessor->display("edit_page_template.tpl");
	}

	public static function getOrder()
	{
		return 200;
	}

	public function getCaption()
	{
		return "Page Templates";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('edit_page_templates');
	}

	public function getHighlightUrls()
	{
		return array
		(
		);
	}
}

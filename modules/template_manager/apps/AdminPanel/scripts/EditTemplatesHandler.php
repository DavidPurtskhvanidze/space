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

class EditTemplatesHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\site_pages\apps\AdminPanel\IMenuItem
{
    protected $moduleName = 'template_manager';
    protected $functionName = 'edit_templates';

    public function respond()
    {
        $appId = \App()->Request->getValueOrDefault('application_id', 'FrontEnd');
        if (!\App()->doesAppExist($appId))
        {
            throw new \lib\Http\NotFoundException('Requested application "' . $this->appId . '" does not exist');
        }

        $templateProcessor = \App()->getTemplateProcessor();
        $templateProcessor->assign("appId", $appId);
        $templateName = \App()->Request['template'];
        $moduleTemplateProviderId = \App()->Request['moduleTemplateProviderId'];
        $action = \App()->Request['action'];
        if (!is_null($templateName) && !is_null($moduleTemplateProviderId))
        {
            $moduleTemplateProvider = new $moduleTemplateProviderId;
            $moduleTemplateManager = \App()->TemplateManagerFactory->createModuleTemplateManager($appId, $moduleTemplateProvider->getModuleName());

            if ($action == 'create')
            {
                if (substr($templateName, -4, 4) != '.tpl')
                    $templateName .= '.tpl';
                $moduleTemplateManager->createTemplate($templateName, "");
                $this->showTemplatesList($moduleTemplateProviderId, $appId, $templateProcessor, $moduleTemplateManager, $templateName);
            }
            elseif ($action == "delete")
            {
                $moduleTemplateManager->deleteTemplate($templateName);
                $this->showTemplatesList($moduleTemplateProviderId, $appId, $templateProcessor, $moduleTemplateManager, $templateName);
            }
            else
            {
                if ($action == "save")
                {
                    $moduleTemplateManager->saveTemplate($templateName, \App()->Request['template_content']);
                    if (!isset($error['is_editable']))
                        \App()->SuccessMessages->addMessage('CHANGES_HAVE_BEEN_SUCCESSFULLY_SAVED');
                }

                $error = $moduleTemplateManager->getError($templateName);
                $templateProcessor->assign("templateIsEditable", empty($error['code']));
                $templateProcessor->assign("isNotEditableReason", $error);

                $templateContent = $moduleTemplateManager->getTemplateContent($templateName);
	            $colorizeManager = new \modules\template_manager\lib\ColorizeManager($moduleTemplateManager->getThemeManager()->getCurrentThemeName());

                $templateProcessor->assign("moduleTemplateProvider", $moduleTemplateProvider);
                $templateProcessor->assign("templateContent", $templateContent);
                $templateProcessor->assign("templateName", $templateName);
                $templateProcessor->assign("currentThemeName", $moduleTemplateManager->getThemeManager()->getCurrentThemeName());
                $templateProcessor->assign('modulesAndFunctionsData', \App()->PageManager->getModuleFunctionParamList($appId));
	            $templateProcessor->assign('themeColors', $colorizeManager->getRules());
                $templateProcessor->display("edit_template.tpl");
            }
        }
        elseif (!is_null($moduleTemplateProviderId))
        {
            $this->showTemplatesList($moduleTemplateProviderId, $appId, $templateProcessor, null, $templateName);
        }
        else
        {
	        $moduleTemplateProviders = \App()->TemplateProvidersManager->getModuleTemplateProviders($appId);
            $moduleTemplateProviders = iterator_to_array($moduleTemplateProviders);
            usort($moduleTemplateProviders, function ($a, $b) {
	            return $a->getModuleTemplateProviderName() > $b->getModuleTemplateProviderName();
            });

            $templateProcessor->assign("moduleTemplateProviders", $moduleTemplateProviders);
            $templateProcessor->display("module_list.tpl");
        }
    }

    private function showTemplatesList($moduleTemplateProviderId, $appId, $templateProcessor, $moduleTemplateManager=null, $templateName)
    {
        $moduleTemplateProvider = new $moduleTemplateProviderId;
        if (is_null($moduleTemplateManager))
            $moduleTemplateManager = \App()->TemplateManagerFactory->createModuleTemplateManager($appId, $moduleTemplateProvider->getModuleName());

        $error = $moduleTemplateManager->getError($templateName);
        $templateIsEditable = (isset($error['isEditable'])and($error['isEditable']==false)) ? false : true;
        $templateProcessor->assign("moduleIsEditable", $templateIsEditable);
        $templateProcessor->assign("isNotEditableReason", $error);
        $templateProcessor->assign("moduleTemplateProvider", $moduleTemplateProvider);
        $templateProcessor->assign("templatesList", $moduleTemplateManager->getTemplatesList());
        $templateProcessor->display("templates_list.tpl");
    }

    public static function getOrder()
    {
        return 300;
    }

    public function getCaption()
    {
        return "Module Templates";
    }

    public function getUrl()
    {
        return \App()->PageRoute->getPageURLById('edit_templates');
    }

    public function getHighlightUrls()
    {
        return array
        ();
    }
}

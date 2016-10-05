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

use apps\AdminPanel\ContentHandlerBase;
use core\ExtensionPoint;
use modules\template_manager\lib\AbstractDesignManager;
use modules\template_manager\lib\ThemeManager;

class EditThemeDesignHandler extends ContentHandlerBase
{
    protected $moduleName = 'template_manager';
    protected $functionName = 'edit_design_files';


    public $templateProcessor;

    /**
     * @var AbstractDesignManager
     */
    public $designManager;

    /**
     * @var \apps\Theme
     */
    public $theme;

    public function respond()
    {
        $appId = \App()->Request->getValueOrDefault('application_id', 'FrontEnd');

        $this->templateProcessor = \App()->getTemplateProcessor();
        $this->templateProcessor->assign("appId", $appId);

        $themeManager = new ThemeManager($appId);
        $this->theme = $themeManager->createTheme(\App()->Request['theme']);
        $designManagerFactories = new ExtensionPoint('modules\template_manager\lib\IDesignManagerFactory');
        $designManager  = null;
        foreach($designManagerFactories as $factory)
        {
            $designManager = $factory->getManager($appId, $this->theme);
            if (!is_null($designManager)) break;
        }
        $this->designManager = $designManager;

        $action = \App()->Request['action'];

        if ($action == 'save')
        {
            $success = $this->designManager->saveDesign(\App()->Request['design_content']);
            if ($success)
                \App()->SuccessMessages->addMessage('CHANGES_HAVE_BEEN_SUCCESSFULLY_SAVED');
        }
        $this->displayForm();
    }

    private function displayForm()
    {
        $error = $this->designManager->getError();
        if (!empty($error))
            \App()->ErrorMessages->addMessage($error['type'], $error['details']);
        $this->templateProcessor->assign("fileIsEditable", empty($error));
        $this->templateProcessor->assign("fileContent", $this->designManager->getDesignContent());
        $this->templateProcessor->assign("themeName", $this->theme->getName());
        $this->templateProcessor->assign("file", $this->designManager->getDesignFileName());
        $this->templateProcessor->display("edit_theme_design.tpl");
    }

}

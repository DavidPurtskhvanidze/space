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
use modules\template_manager\lib\ColorizeManager;

class EditColorsHandler extends ContentHandlerBase
{
    protected $moduleName = 'template_manager';
    protected $functionName = 'edit_colors';


    public $templateProcessor;
	private $themeName;
    /**
     * @var ColorizeManager
     */
	private $colorizeManager;

    public function respond()
    {
	    $this->themeName = \App()->Request['theme'];

	    $this->templateProcessor = \App()->getTemplateProcessor();
	    $this->colorizeManager = (new ColorizeManager($this->themeName))
            ->setThemeName($this->themeName)
            ->setFileSystem(\App()->FileSystem);

	    $action = \App()->Request['action'];
	    $canPerform = true;

	    $validators = new ExtensionPoint('modules\template_manager\apps\AdminPanel\IThemeColorizeActionValidator');
	    foreach ($validators as $validator)
	    {
		    $validator->setAction(\App()->Request['action']);
		    $canPerform &= $validator->isValid();
	    }

	    if ($canPerform)
	    {
		    if ($action == 'save')
		    {
                $request = \App()->Request->getRequest();
			    unset($request['action']);
			    unset($request['theme']);
                unset($request['secure_token']);
			    $success = $this->colorizeManager->save($request);
			    if ($success)
				    \App()->SuccessMessages->addMessage('CHANGES_HAVE_BEEN_SUCCESSFULLY_SAVED');
		    }
	    }

        $this->displayForm();
    }

    private function displayForm()
    {
	    $this->templateProcessor->assign('themeName', $this->themeName);
	    $this->templateProcessor->assign('themeColors', $this->colorizeManager->getRules());
        $this->templateProcessor->display("edit_colors.tpl");
    }

}

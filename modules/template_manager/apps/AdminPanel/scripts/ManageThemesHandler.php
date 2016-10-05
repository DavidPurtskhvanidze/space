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
use lib\Http\NotFoundException;
use lib\Http\RedirectException;
use modules\site_pages\apps\AdminPanel\IMenuItem;
use modules\template_manager\lib\ThemeManager;

class ManageThemesHandler extends ContentHandlerBase implements IMenuItem
{
	protected $moduleName = 'template_manager';
	protected $functionName = 'theme_editor';
    protected $itemAppId = 'FrontEnd';
    protected $currentAppId = null;

    public function __construct()
    {
        $this->currentAppId = \App()->Request->getValueOrDefault('application_id', $this->itemAppId);
        if (!\App()->doesAppExist($this->currentAppId))
        {
            throw new NotFoundException('Requested application "' . $this->currentAppId . '" does not exist');
        }
    }

	public function respond()
	{
		$themeManager = new ThemeManager($this->currentAppId);

		$canPerform = true;
		/**
		 * @var \modules\template_manager\apps\AdminPanel\IThemeActionValidator[] $validators
		 */
		$validators = new ExtensionPoint('modules\template_manager\apps\AdminPanel\IThemeActionValidator');
		foreach ($validators as $validator)
		{
			$validator->setAction(\App()->Request['action']);
			$canPerform &= $validator->isValid();
		}

		if ($canPerform)
		{
			try
			{
				switch (\App()->Request['action'])
				{
					case "new_theme":
						$newThemeName = \App()->Request['new_theme'];
						$baseThemeName = \App()->Request['base_theme'];
						$newTheme = $themeManager->addTheme($newThemeName, $baseThemeName);
						throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?application_id={$this->currentAppId}&action=install_theme&theme=$newThemeName&module_name={$newTheme->getModuleName()}");
					case "make_current":
						$themeName = \App()->Request['theme'];
						$themeManager->makeThemeCurrent($themeName);
						throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?application_id={$this->currentAppId}");
					case "delete_theme":
						$themeName = \App()->Request['theme'];
						$themeManager->deleteTheme($themeName);
						throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?application_id={$this->currentAppId}");
					case "install_theme":
						$themeName = \App()->Request['theme'];
						$moduleName = \App()->Request['module_name'];
						\App()->ModuleManager->installModules([$moduleName]);
						\App()->SuccessMessages->fetchMessages();
						throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?application_id={$this->currentAppId}&action=make_current&theme=$themeName");
				}
			}
			catch (\modules\template_manager\lib\Exception $e)
			{
				\App()->ErrorMessages->addMessage($e->getMessage(), $e->getData());
			}
		}

		$themesTree = $themeManager->getThemesTree();
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign("appId", $this->currentAppId);
		$templateProcessor->assign("rootTheme", $themesTree->getItem(\App()->SystemSettings->getSettingForApp($this->currentAppId, 'DefaultTheme')));
		$templateProcessor->display("theme_editor.tpl");
	}

	public static function getOrder()
	{
		return 400;
	}

	public function getCaption()
	{
		return "Themes";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('edit_themes');
	}

	public function getHighlightUrls()
	{
        if ($this->itemAppId == $this->currentAppId)
        {
            return [
                [
                    'uri' => \App()->PageRoute->getSystemPageURI('template_manager', 'edit_colors'),
                    'params' => ['application_id' => 'FrontEnd']
                ],
                [
                    'uri' => \App()->PageRoute->getSystemPageURI('template_manager', 'edit_design_files'),
                    'params' => ['application_id' => $this->currentAppId]
                ],
            ];
        }
		return [];
	}
}

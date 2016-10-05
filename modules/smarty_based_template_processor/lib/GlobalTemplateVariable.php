<?php
/**
 *
 *    Module: smarty_based_template_processor v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: smarty_based_template_processor-7.5.0-1
 *    Tag: tags/7.5.0-1@19835, 2016-06-17 13:21:56
 *
 *    This file is part of the 'smarty_based_template_processor' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\smarty_based_template_processor\lib;

use core\ExtensionPoint;
use core\IService;
use modules\miscellaneous\lib\ArrayWrapperWithEscaping;

class GlobalTemplateVariable implements IService
{
	public function init()
	{
		$this->setGlobalTemplateVariable('site_url', \App()->SystemSettings['SiteUrl']);
		$this->setGlobalTemplateVariable('settings', \App()->SettingsFromDB->getSettings());
		$this->setGlobalTemplateVariable('notification_email', \App()->SettingsFromDB->getSettingByName('notification_email'));
		$this->setGlobalTemplateVariable('custom_settings', \App()->CustomSettings->getSettingsToRegister());
		$this->setGlobalTemplateVariable('front_end_url', \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl'));
		$this->setGlobalTemplateVariable('REQUEST', new ArrayWrapperWithEscaping($_REQUEST), false);

        $templateVariables = new ExtensionPoint('modules\smarty_based_template_processor\lib\ITemplateVariable');
        foreach ($templateVariables as $templateVariable)
        {
            $this->setGlobalTemplateVariable($templateVariable->getKey(), $templateVariable->getValue(), $templateVariable->inGlobalArray);
        }
    }

	public function getGlobalTemplateVariables()
	{
		return $GLOBALS['TEMPLATE_VARIABLES'];
	}

	private function getGlobalTemplateVariable($variable_name)
	{
		return (isset($GLOBALS['TEMPLATE_VARIABLES'][$variable_name])) ? $GLOBALS['TEMPLATE_VARIABLES'][$variable_name] : null;
	}

	public function setGlobalTemplateVariable($name, $value, $in_global_array = true)
	{
		if ($in_global_array) $GLOBALS['TEMPLATE_VARIABLES']['GLOBALS'][$name] = $value;
		else $GLOBALS['TEMPLATE_VARIABLES'][$name] = $value;
	}

	public function appendPageTitle($page_title)
	{
		$title = $this->getPageTitle();
		$page_title .= (!empty($title)) ? ' ' . $title : '';
		$this->setGlobalTemplateVariable('TITLE', $page_title, false);
	}

	public function getPageTitle()
	{
		return $this->getGlobalTemplateVariable('TITLE');
	}

	public function appendPageKeywords($page_keywords)
	{
		$keywords = $this->getPageKeywords();
		$page_keywords .= (!empty($keywords)) ? ' ' . $keywords : '';
		$this->setGlobalTemplateVariable('KEYWORDS', trim($page_keywords), false);
	}

	public function getPageKeywords()
	{
		return $this->getGlobalTemplateVariable('KEYWORDS');
	}

	public function appendPageDescription($page_description)
	{
		$description = $this->getPageDescription();
		$page_description .= (!empty($description)) ? ' ' . $description : '';
		$this->setGlobalTemplateVariable('DESCRIPTION', trim($page_description), false);
	}

	public function getPageDescription()
	{
		return $this->getGlobalTemplateVariable('DESCRIPTION');
	}

	public function setCurrentUserInfo($current_user_info)
	{
		$this->setGlobalTemplateVariable('current_user', $current_user_info);
	}

    public function setMetaTags($tags)
    {
        $this->setGlobalTemplateVariable('META_TAGS', $tags, false);
    }

    public function getMetaTags()
    {
        return $this->getGlobalTemplateVariable('META_TAGS');
    }
}

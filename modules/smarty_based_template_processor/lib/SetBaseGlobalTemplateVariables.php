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

class SetBaseGlobalTemplateVariables implements \core\IOnPageConstructorGetResponse
{
	/**
	 * @param \core\SitePageConfig $page_config
	 */
	public function perform($page_config)
	{
		\App()->GlobalTemplateVariable->appendPageTitle($page_config->getPageTitle());
		\App()->GlobalTemplateVariable->setGlobalTemplateVariable('current_page_uri', $page_config->getPageUri());
		\App()->GlobalTemplateVariable->setGlobalTemplateVariable('current_base_uri', $page_config->getBaseUri());
		\App()->GlobalTemplateVariable->setGlobalTemplateVariable('current_page_config', new \core\PageConfigArrayAdapter($page_config));
		\App()->GlobalTemplateVariable->appendPageKeywords($page_config->getPageKeywords());
		\App()->GlobalTemplateVariable->appendPageDescription($page_config->getPageDescription());
	}
}

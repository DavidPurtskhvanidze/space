<?php
/**
 *
 *    Module: site_pages v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: site_pages-7.5.0-1
 *    Tag: tags/7.5.0-1@19834, 2016-06-17 13:21:53
 *
 *    This file is part of the 'site_pages' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\site_pages\apps\AdminPanel\scripts;

class RegisterPageButtonHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'site_pages';
	protected $functionName = 'register_page_button';
	protected $rawOutput = true;

	public function respond()
	{
        if (isset($_REQUEST['pageInfo'])) {
            $_REQUEST['pageInfo']['parameters'] = $this->array2String($_REQUEST['pageInfo']['parameters']);
            $template_processor = \App()->getTemplateProcessor();
            $template_processor->assign('pageInfo', $_REQUEST['pageInfo']);
            $template_processor->assign('caption', $_REQUEST['caption']);
            $template_processor->display('register_page_button.tpl');
        }
	}

    private function array2String($params) {
        if (empty ($params))
            return false;

        return urlencode(json_encode($params));
    }
}
?>

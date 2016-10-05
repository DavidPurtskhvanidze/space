<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\FrontEnd\scripts;

class ClearComparisonHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Clear Comparison';
	protected $moduleName = 'classifieds';
	protected $functionName = 'clear_comparison';

	public function respond()
	{
		
        $errors = array();

        $action = \App()->ObjectMother->createClearComparisonAction();
        if ($action->canPerform())
        {
            $action->perform();
            throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('compared_listings'));
        }
        else
        {
            $errors = $action->getErrors();
        }

        $template_processor = \App()->getTemplateProcessor();
        $template_processor->assign('errors', $errors);
        $template_processor->display('clear_comparison.tpl');
    }
}
?>

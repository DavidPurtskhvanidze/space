<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\main\apps\AdminPanel;

class AccessControlValidator implements \apps\AdminPanel\IModuleFunctionExecuteValidator
{
    public function isValid($module, $function)
    {
        return \App()->AccessControlManager->hasAccess($module, $function);
    }

    public function displayInvalidMessage()
    {
        \App()->ErrorMessages->addMessage('ACCESS_DENIED', array(), 'main');
        \App()->getTemplateProcessor()->display("main^access_control_message.tpl");
    }

    /**
     * Adds error message as comment to message stack
     */
    public function displayInvalidMessageAsComment($moduleName, $functionName)
    {
        $templateProcessor = \App()->getTemplateProcessor();
        $templateProcessor->assign('moduleName', $moduleName);
        $templateProcessor->assign('functionName', $functionName);
        $templateProcessor->display('main^access_control_message_as_comment.tpl');
    }
}

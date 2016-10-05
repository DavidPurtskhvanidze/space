<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\apps\FrontEnd\scripts;

use apps\FrontEnd\ContentHandlerBase;

class LoginHandler extends ContentHandlerBase
{
    protected $displayName = 'Login';
    protected $moduleName = 'users';
    protected $functionName = 'login';

    public function respond()
    {

        \App()->GlobalTemplateVariable->setGlobalTemplateVariable('loginFormWasAlreadyDisplayed', true);
        $template_processor = \App()->getTemplateProcessor();

        $errors = isset($_REQUEST['errorMessage']) ? [$_REQUEST['errorMessage'] => 1] : [];

        if (\App()->UserManager->isUserLoggedIn())
        {
            $template_processor->display("already_logged_in.tpl");
        }
        else
        {
            if (isset($_REQUEST['action']) && $_REQUEST['action'] == "login")
            {
                $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : null;
                $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
                $keep_signed = isset($_REQUEST['keep']) ? true : false;

                $logged_in = \App()->UserManager->login($username, $password, $keep_signed, $errors);

                if ($logged_in)
                {
                    $redirectAfterLoginAction = \App()->ObjectMother->createRedirectAfterLoginAction(\App()->Request['HTTP_REFERER'], \App()->Request['QUERY_STRING'], \App()->Navigator->getURI());
                    $redirectAfterLoginAction->perform();
                }
            }

            if (isset($_REQUEST['WARNING']) && !empty($_REQUEST['WARNING']))
            {
                $template_processor->assign("warning", $_REQUEST['WARNING']);
            }

            if (isset($_REQUEST['HTTP_REFERER']) && !empty($_REQUEST['HTTP_REFERER']))
            {
                $template_processor->assign("HTTP_REFERER", $_REQUEST['HTTP_REFERER']);
            }

            if (isset($_REQUEST['return_back']) && $_REQUEST['return_back'] === 'true')
            {
                $template_processor->assign("HTTP_REFERER", $_SERVER['HTTP_REFERER']);
            }

            $template_processor->assign("errors", $errors);
            $template_processor->assign("QUERY_STRING", isset($_REQUEST['QUERY_STRING']) ? $_REQUEST['QUERY_STRING'] : $_SERVER['QUERY_STRING']);

	        $template = \App()->Request->getValueOrDefault('template', "login.tpl");
	        $template_processor->display($template);
        }

    }
}

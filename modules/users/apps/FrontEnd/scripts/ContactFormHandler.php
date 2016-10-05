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

// version 5 wrapper header

class ContactFormHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Contact form';
	protected $moduleName = 'users';
	protected $functionName = 'contact_form';
	protected $parameters = array('display_template');
	protected $rawOutput = true;

    public function respond()
    {

        // end of version 5 wrapper header



        if (isset($_REQUEST['passed_parameters_via_uri'])) {
            $passed_parameters_via_uri = \App()->UrlParamProvider->getParams();
            $user_sid = isset($passed_parameters_via_uri[0]) ? $passed_parameters_via_uri[0] : null;
        }
        elseif (isset($_REQUEST['user_sid']))
        {
            $user_sid = $_REQUEST['user_sid'];
        }
        else
        {
            $user_sid = null;
        }

        $request_data = isset($_REQUEST['action']) && $_REQUEST['action'] == 'resend_message' ? unserialize($_REQUEST['serialized_data']) : $_REQUEST;

        $action = \App()->ObjectMother->createSendUserContactFormMessageAction($request_data, $user_sid);
        if ($action->canPerform()) {
            $action->perform();
        }
        else
        {
            $template_processor = \App()->getTemplateProcessor();
            $template_processor->assign("errors", $action->getErrors());
            $template_processor->display("errors.tpl");
        }

        //  version 5 wrapper footer

    }
}

// end of version 5 wrapper footer
?>

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

class UserDetailsHandler extends \apps\FrontEnd\ContentHandlerBase
{
    protected $displayName = 'User details';
    protected $moduleName = 'users';
    protected $functionName = 'user_details';
    protected $parameters = ['display_template'];

    public function respond()
    {
        $template_processor = \App()->getTemplateProcessor();
        $userSid = $this->getUserSid();
        $user = \App()->UserManager->getObjectBySID($userSid);
        if (!empty($user)) {
            $template_processor->assign("user", \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($user));
            $template_processor->display(isset(\App()->Request['display_template']) ? \App()->Request['display_template'] : 'user_details.tpl');
        } else {
            \App()->ErrorMessages->addMessage('Invalid username/password combination. Please try again.');
        }
    }

    private function getUserSid()
    {
        if (isset(\App()->Request['passed_parameters_via_uri'])) {

            $passed_parameters_via_uri = \App()->UrlParamProvider->getParams();

            $userSid = isset($passed_parameters_via_uri[0]) ? $passed_parameters_via_uri[0] : null;

        } elseif (isset(\App()->Request['user_sid'])) {

            $userSid = \App()->Request['user_sid'];

        } else {

            $userSid = null;

        }

        return $userSid;
    }
}

?>

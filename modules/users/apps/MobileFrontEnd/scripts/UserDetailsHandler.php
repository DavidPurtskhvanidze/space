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


namespace modules\users\apps\MobileFrontEnd\scripts;

// version 5 wrapper header

class UserDetailsHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'User details';
	protected $moduleName = 'users';
	protected $functionName = 'user_details';
	protected $parameters = array('display_template');

	public function respond()
	{
		
// end of version 5 wrapper header


if (isset($_REQUEST['passed_parameters_via_uri'])) {

	$passed_parameters_via_uri = \App()->UrlParamProvider->getParams();

	$user_sid = isset($passed_parameters_via_uri[0]) ? $passed_parameters_via_uri[0] : null;

} elseif (isset($_REQUEST['user_sid'])) {

	$user_sid = $_REQUEST['user_sid'];

} else {

	$user_sid = null;

}

$template_processor = \App()->getTemplateProcessor();

$user = \App()->UserManager->getObjectBySID($user_sid);
if(!empty($user))
{
	$template_processor->assign("user", \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($user));
	$template_processor->display(isset($_REQUEST['display_template']) ? $_REQUEST['display_template'] : 'user_details.tpl');
}
else
{
	$errors['NO_SUCH_USER'] = 1;
	
	$template_processor->assign('errors',$errors);
	
	$template_processor->display('errors.tpl');
}
//  version 5 wrapper footer

	}
}
// end of version 5 wrapper footer
?>

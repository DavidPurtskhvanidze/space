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

class ActivateAccountHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Account activation';
	protected $moduleName = 'users';
	protected $functionName = 'activate_account';

	public function respond()
	{
		
// end of version 5 wrapper header

$template_processor = \App()->getTemplateProcessor();

$INFO = NULL;
$ERROR = NULL;

if ( !isset($_REQUEST['username'], $_REQUEST['activation_key']) ) {

	$ERROR['PARAMETERS_MISSED'] = 1;

} elseif ( !$user_info = \App()->UserManager->getUserInfoByUserName($_REQUEST['username']) ) {

	$ERROR['USER_NOT_FOUND'] = 1;

} elseif ($user_info['activation_key'] != $_REQUEST['activation_key']) {

	$ERROR['INVALID_ACTIVATION_KEY'] = true;

} elseif ( !\App()->UserManager->activateUserByUserName($_REQUEST['username']) ) {

	$ERROR['CANNOT_ACTIVATE'] = TRUE;

} else {

	$INFO['ACCOUNT_ACTIVATED'] = 1;

}

$template_processor->assign("INFO", $INFO);
$template_processor->assign("ERROR", $ERROR);
$template_processor->display("activate_account.tpl");
//  version 5 wrapper footer

	}
}
// end of version 5 wrapper footer
?>

<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\FrontEnd\scripts;

// version 5 wrapper header

class RateHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Rate';
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'rate';

	public function respond()
	{
		
// end of version 5 wrapper header

$current_rate= isset($_REQUEST['current_rate']) ? $_REQUEST['current_rate'] : null;
$object_sid = isset($_REQUEST['object_sid']) ? $_REQUEST['object_sid'] : null;
$object_type = isset($_REQUEST['object_type']) ? $_REQUEST['object_type'] : null;
$field_sid = isset($_REQUEST['field_sid']) ? $_REQUEST['field_sid'] : null;
$rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;
if (isset($_REQUEST["HTTP_REFERER"]))
{
	$HTTP_REFERER = $_REQUEST["HTTP_REFERER"];
}
elseif (isset($_SERVER["HTTP_REFERER"]))
{
	$HTTP_REFERER = $_SERVER["HTTP_REFERER"];
}
$rate=intval($rate);
$errors = array();

if(empty($current_rate))
{
	if($rate < 1 || $rate > 5)
	{
		$errors[] = 'RATE_IS_NOT_VALID';
	}

	if (empty($object_sid))
	{
		$errors[] = 'OBJECT_SID_IS_EMPTY';
	}

	if (empty($field_sid))
	{
		$errors[] = 'FIELD_SID_IS_EMPTY';
	}

	if (empty($object_type))
	{
		$errors[] = 'OBJECT_TYPE_IS_EMPTY';
	}

	if (!\App()->UserManager->isUserLoggedIn())
	{
		$errors[] = 'NOT_LOGGED_IN';
	}

	if (empty($errors))
	{
		$current_rate = \App()->ObjectMother->createRatingManager($object_type)->addRating($object_sid, $field_sid, $rate);
		throw new \lib\Http\RedirectException($HTTP_REFERER);
	}
}

$template_processor = \App()->getTemplateProcessor();

$template_processor->assign("ERRORS", $errors);
$template_processor->assign("current_rate", $current_rate);
$template_processor->assign("object_type", $object_type);
$template_processor->assign("HTTP_REFERER", $HTTP_REFERER);

$template_processor->display("rate.tpl");
//  version 5 wrapper footer

	}
}
// end of version 5 wrapper footer
?>

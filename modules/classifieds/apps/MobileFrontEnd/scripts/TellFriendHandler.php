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


namespace modules\classifieds\apps\MobileFrontEnd\scripts;

class TellFriendHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'Tell a friend';
	protected $moduleName = 'classifieds';
	protected $functionName = 'tell_friend';

	public function respond()
	{
        $handler = new \modules\classifieds\apps\FrontEnd\scripts\TellFriendHandler();
        $handler->respond();
	}
}

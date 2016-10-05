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


namespace modules\main\lib;

class TwoFactorAdminManager extends  AdminManager
{
    public function admin_login($username, $password)
    {
	    if ( ! parent::admin_login($username, $password) && ! LoginFailures::getInstance()->isNotHuman())
	    {
		    LoginFailures::getInstance()->incrementFailures();
		    \App()->ErrorMessages->addMessage("ATTEMPTS_LEFT", array('count' => LoginFailures::getInstance()->getAttemptsLeft()));
		    return false;
	    }
	    LoginFailures::getInstance()->delete();
	    return true;
    }
}

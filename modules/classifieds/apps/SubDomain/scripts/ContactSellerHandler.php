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


namespace modules\classifieds\apps\SubDomain\scripts;

class ContactSellerHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Contact Seller';
	protected $moduleName = 'classifieds';
	protected $functionName = 'contact_seller';
	protected $rawOutput = true;

    public function respond()
    {
	    $handler = new \modules\classifieds\apps\FrontEnd\scripts\ContactSellerHandler();
	    $handler->respond();
    }
}
?>

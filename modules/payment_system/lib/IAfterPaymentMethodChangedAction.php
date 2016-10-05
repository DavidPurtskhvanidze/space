<?php
/**
 *
 *    Module: payment_system v.7.4.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_system-7.4.0-1
 *    Tag: tags/7.4.0-1@19076, 2015-12-14 12:49:32
 *
 *    This file is part of the 'payment_system' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_system\lib;

interface IAfterPaymentMethodChangedAction
{
	public function perform();
}

<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan\apps\AdminPanel;

/**
 * Delete membership plan validator
 * 
 * Interface designed for validating delete membership plan action in AdminPanel. If it returns false, membership plan will not be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeleteMembershipPlanValidator
{
	/**
	 * Setter of membership plan sid
	 * @param int $membershipPlanSid
	 */
	public function setMembershipPlanSid($membershipPlanSid);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}

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


namespace modules\classifieds\lib\Calendar;

class CalendarManager
{
	private $DB;

	public function setDB($DB)
	{
		$this->DB = $DB;
	}
	function getPeriods($listingSid, $fieldSid)
	{
		$data = $this->DB->query("select `sid`, `from`, `to`, `status`, `comment` from `classifieds_listing_field_calendar` where `field_sid` = ?n and `listing_sid` = ?n", $fieldSid, $listingSid);
		foreach($data as $key=>$range){
            
            $data[$key]['RangeArray'] = $this->createDateRangeArray($range['from'],$range['to']);
        }
        return $data;
	}

	function addPeriod($listingSid, $fieldSid, $from, $to, $status, $comment)
	{
		$id = $this->DB->query("insert into `classifieds_listing_field_calendar` (`from`, `to`, `status`, `field_sid`, `listing_sid`, `comment`) values (?s, ?s, ?s, ?n, ?n, ?s)", $from, $to, $status, $fieldSid, $listingSid, $comment);
		return $id;
	}

	function sendBookListingRequest($sender_email_address, $sender_name, $listingSid, $fieldSid, $from, $to, $comment)
	{
		$listing = \App()->ListingManager->getObjectBySID($listingSid);
		$email_address = is_null($listing->getPropertyValue('user')) ? \App()->SettingsFromDB->getSettingByName('notification_email') : $listing->getPropertyValue('user')->getPropertyValue('email');
		return \App()->EmailService->send($email_address, 'email_template:book_request', array(
			'listing' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($listing),
			'field_sid' => $fieldSid,
			'sender_name' => $sender_name,
			'sender_email' => $sender_email_address,
			'period_start' => $from,
			'period_end' => $to,
			'comment' => $comment,
		), $sender_email_address);
	}

	function deletePeriod($sids)
	{
		foreach($sids as $sid)
		{
			$this->DB->query("delete from `classifieds_listing_field_calendar` where `sid` = ?n", intval($sid));
		}
	}

	function deleteCalendarByListingSID($sid)
	{
		$this->DB->query("delete from `classifieds_listing_field_calendar` where `listing_sid` = ?n", intval($sid));
	}
    
    function createDateRangeArray($strDateFrom,$strDateTo) {
      // takes two dates formatted as YYYY-MM-DD and creates an
      // inclusive array of the dates between the from and to dates.

      // could test validity of dates here but I'm already doing
      // that in the main script

      $aryRange=array();

      $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
      $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

      if ($iDateTo>=$iDateFrom) {
        array_push($aryRange,date('j, n, Y',$iDateFrom)); // first entry

        while ($iDateFrom<$iDateTo) {
          $iDateFrom+=86400; // add 24 hours
          array_push($aryRange,date('j, n, Y',$iDateFrom));
        }
      }
      return $aryRange;
    }

}

?>

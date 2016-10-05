<?php
/**
 *
 *    Module: listing_repost v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_repost-7.5.0-1
 *    Tag: tags/7.5.0-1@19795, 2016-06-17 13:19:57
 *
 *    This file is part of the 'listing_repost' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_repost;
 
class ListingRepostStatusManager
{
	function getFacebookRepostStatus($listingSid)
	{
		$res = \App()->DB->getSingleValue("SELECT `facebook_repost_status` FROM `classifieds_listings` WHERE `sid` = ?n", $listingSid);
		return $res;
	}

	function setFacebookRepostStatus($listingSid, $status)
	{
		return \App()->DB->query("UPDATE `classifieds_listings` SET `facebook_repost_status` = ?n WHERE `sid` = ?n", $status, $listingSid);
	}
	function getTwitterRepostStatus($listingSid)
	{
		$res = \App()->DB->getSingleValue("SELECT `twitter_repost_status` FROM `classifieds_listings` WHERE `sid` = ?n", $listingSid);
		return $res;
	}

	function setTwitterRepostStatus($listingSid, $status)
	{
		return \App()->DB->query("UPDATE `classifieds_listings` SET `twitter_repost_status` = ?n WHERE `sid` = ?n", $status, $listingSid);
	}
}

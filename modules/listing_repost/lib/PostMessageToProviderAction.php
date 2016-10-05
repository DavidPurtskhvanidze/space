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


namespace modules\listing_repost\lib;

class PostMessageToProviderAction
{
	private $provider;
	private $message;
	private $accessData;

	public function setProvider($provider)
	{
		$this->provider = $provider;
	}

	public function setAccessData($accessData)
	{
		$this->accessData = $accessData;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	function perform()
	{
		$this->provider->postMessage($this->message, $this->accessData);
	}
}

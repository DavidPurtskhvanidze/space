<?php
/**
 *
 *    Module: recent_tweets v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: recent_tweets-7.3.0-1
 *    Tag: tags/7.3.0-1@18563, 2015-08-24 13:38:12
 *
 *    This file is part of the 'recent_tweets' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\recent_tweets\lib;

require_once "OAuth/twitteroauth.php";

class TwitterTimelineManager
{
	const user_timeline_request_uri = "/statuses/user_timeline.json";
	private $_enableCaching;
	private $_error;
	
	public function __construct($enableCaching = false)
	{
		$this->_enableCaching = $enableCaching;
	}
	
	public function getTimeline($count)
	{
		$this->_error = null;
		if ($this->_enableCaching)
		{
			$twitterTimelineData = \App()->CacheManager->invoke(
				$this, 'getTwitterTimelineData', array($count),
				'recent_tweets', $this->buildRequestUrl($count),
				\App()->SettingsFromDB->getSettingByName('recentTweets_lifeTime')
			);

			if (is_null($twitterTimelineData))
			{
				$twitterTimelineData = \App()->CacheManager->getDataIgnoringLifetime('recent_tweets', $this->buildRequestUrl($count));
				if (is_null($twitterTimelineData))
				{
					throw new \modules\recent_tweets\lib\Exception($this->_error);
				}
			}
		}
		else
		{
			$twitterTimelineData = $this->getTwitterTimelineData($count);
		}

		if (is_array($twitterTimelineData))
		{
			return array(
				'user'		=> $this->_extractUserData($twitterTimelineData),
				'tweets'	=> $this->_extractTweetData($twitterTimelineData),
			);
		}
			
		return null;
	}
	
	protected function _extractUserData($tweetDataArray)
	{
		$tweetData = array_shift($tweetDataArray);
		
		return array(
			'id'		=> $tweetData->user->id_str,
			'full_name'		=> $tweetData->user->name,
			'screen_name'	=> $tweetData->user->screen_name,
			'image_url'		=> $tweetData->user->profile_image_url,
			'description'	=> $tweetData->user->description,
			'location'		=> $tweetData->user->location,
			'created_at'	=> $tweetData->user->created_at,
			'web_url'		=> $tweetData->user->url,
			'twitter_url'	=> 'http://twitter.com/'.$tweetData->user->screen_name,
			'reply_link'	=> 'http://twitter.com/intent/tweet?text='.urlencode( '@'.$tweetData->user->screen_name )
		);
	}
	
	protected function _extractTweetData($tweetDataArray)
	{
		$result = array();
		foreach ($tweetDataArray as $tweetData)
		{
            $links = array();
			$text = $tweetData->text;
            $text = preg_replace('/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;\'">\:\s\<\>\)\]\!])/i', '<span class="twitterTextUrl"><a title="$1" href="$1" target="_blank">$1</a></span>', $text);
            $text = preg_replace('/(\B)@([_a-z0-9]+)/i', '$1<span class="twitterReply"><a title="$2" href="http://twitter.com/$2" target="_blank">$2</a></span>', $text);
            preg_match('/<a.*href=\".*\">.*<\/a>/', $text, $links);

            $result[] = array(
				'text'			=> trim($text),
				'id'			=> $tweetData->id_str,
				'replied_to'	=> $tweetData->in_reply_to_screen_name,
				'posted_date'	=> $tweetData->created_at,
				'full_name'		=> $tweetData->user->name,
				'links'		=> $links,
			);

		}
		
		return $result;
	}

	public function getTwitterTimelineData($count)
	{
		if (!$this->isTwitterSetUp())
		{
			\App()->ErrorMessages->addMessage('TWITTER_APP_IS_NOT_CONFIGURED');
			return null;
		}

		$result = $this->_makeRequest($this->buildRequestUrl($count));
		if ($result)
		{
			if (isset($result->errors))
			{
				\App()->ErrorMessages->addMessage('TWITTER_API_ERROR_RESPONSE', array('errors' => $result->errors));
				$result = null;
			}
		}
		else
		{
			$result = null;
		}

		return $result;
	}
	
	protected function buildRequestUrl($count)
	{
		$data = array(
			'screen_name' => \App()->SettingsFromDB->getSettingByName('recentTweets_screenName'),
			'count' => $count
		);

		return self::user_timeline_request_uri . '?' . http_build_query($data);
	}
	
	protected function _makeRequest($url)
	{
		$consumerKey = \App()->SettingsFromDB->getSettingByName('twitter_consumer_key');
		$consumerSecret = \App()->SettingsFromDB->getSettingByName('twitter_consumer_secret');
		$accessToken = \App()->SettingsFromDB->getSettingByName('twitter_access_token');
		$accessTokenSecret = \App()->SettingsFromDB->getSettingByName('twitter_access_token_secret');
		$connection = new \TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
		$result = $connection->get($url);
		return $result;
	}

	public function isTwitterSetUp()
	{
		$requiredSettings = array('twitter_consumer_key', 'twitter_consumer_secret', 'twitter_access_token', 'twitter_access_token_secret');
		foreach ($requiredSettings as $settingName)
		{
			$settingValue = \App()->SettingsFromDB->getSettingByName($settingName);
			if (empty($settingValue))
			{
				return false;
			}
		}
		return true;
	}
}

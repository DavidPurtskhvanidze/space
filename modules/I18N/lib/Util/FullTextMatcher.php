<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\lib\Util;

class FullTextMatcher
{
	function setQuery($query)
	{
		$this->query_tokens = preg_split("/\s+/", $query);
	}
	
	function match($subject)
	{
		$charlistToEscape = '$()*+-./?[\]^{|}';
		foreach ($this->query_tokens as $token)
		{
			$token = addcslashes($token, $charlistToEscape);
			if (!preg_match("/\b".$token."/i", $subject))
			{
				return false;
			}
		}
		return true;
	}
}

?>

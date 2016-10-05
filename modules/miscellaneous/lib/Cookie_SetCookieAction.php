<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class Cookie_SetCookieAction {
	
	var $cookie_data_source;
	var $name;
 	var $value;
	var $expire;
 	var $errors;
 	private $validated = false;
	private $cookieMaxSize;

	public function setCookieMaxSize($cookieMaxSize)
	{
		$this->cookieMaxSize = $cookieMaxSize;
	}
    
 	function __construct($cookie_data_source, $name, $value, $expire)
 	{
 		$this->cookie_data_source = $cookie_data_source;
 		$this->name = $name;
 		$this->value = $value;
		$this->expire = $expire;
    }
    
    function canPerform()
    {
    	if (!$this->validated) $this->_validate();
        return empty($this->errors);
    }
    
    function _validate()
    {
        $cookie_size = $this->_getCookieSizeWithNewCookie();
        if ($cookie_size > $this->cookieMaxSize) $this->errors[] = 'COOKIE_MAX_SIZE_EXCEEDED';
        $this->validated = true;
    }
    
    function _getCookieSizeWithNewCookie()
    {
    	$cookies = $this->cookie_data_source->getCookies();
    	$cookies = $this->_getOneDimensionArray($cookies);
    	$cookies[$this->name] = $this->value;
    	$cookies = array_map("urlencode", $cookies);
    	$cookies_header_string = join("; ", $cookies);
    	return strlen($cookies_header_string);
	}
	
	function _getOneDimensionArray($array, $parent_key = null)
	{	
		$output = array();
		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$output += $this->_getOneDimensionArray($value, $key);
			}
			elseif(is_null($parent_key))
			{
				$output["$key"] = $value;
			}
			else
			{
				$output[$parent_key . "[" . $key . "]"] = $value;
			}
		}
		return $output;
	}
	
	function getErrors()
	{
    	if (!$this->validated) $this->_validate();
		return $this->errors;
	}
	
	function perform()
	{
    	if (!$this->validated) $this->_validate();
		$this->cookie_data_source->setcookie($this->name, $this->value, $this->expire);
	}	
}

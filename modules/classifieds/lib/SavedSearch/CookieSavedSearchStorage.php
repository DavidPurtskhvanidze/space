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


namespace modules\classifieds\lib\SavedSearch;

class CookieSavedSearchStorage implements ISavedSearchStorage
{
	const NULL_BYTE_PLACEHOLDER = "NULL_BYTE";

	private $cookieService;
	public function setCookieService($s){$this->cookieService = $s;}

	private $cookieName;
	public function setCookieName($name){$this->cookieName = $name;}

	public function getSearches()
	{
		$saved_searches = array();
		$searches = $this->getSavedSearchesFromCookie();
		foreach(array_keys($searches) as $k)
		{
			$saved_searches[$k]['id'] = $k;
			$saved_searches[$k]['sid'] = $k;
			$saved_searches[$k]['name'] = $k;
			$saved_searches[$k]['query_string'] = http_build_query($searches[$k]->getRequest());
			$saved_searches[$k]['search_results_uri'] = $searches[$k]->getSearchResultsUri();
		}
		return $saved_searches;
	}

	public function saveSearch($name, $search, &$errors)
	{
    	$savedSearches = $this->getSavedSearchesFromCookie();
    	$savedSearches[$name] = $search;
        $action = $this->cookieService->createSetCookieAction($this->cookieName, $this->serializeAndFilter($savedSearches));
        if ($action->canPerform())
        {
            $action->perform();
            return true;
        } 
        else 
        {
        	$errors = $action->getErrors();
        	return false;
        }
	}

	public function deleteSearch($id)
	{
    	$savedSearches = $this->getSavedSearchesFromCookie();
    	unset($savedSearches[$id]);
        $this->cookieService->createSetCookieAction($this->cookieName, $this->serializeAndFilter($savedSearches))->perform();
	}

	public function enbaleAutonotification($id)
	{
		throw new \Exception("Autonotification cannot be enable for searches stored in cookies");
	}

	public function disableAutonotification($id)
	{
		throw new \Exception("Autonotification cannot be enable for searches stored in cookies");
	}

	private function getSavedSearchesFromCookie()
	{
    	$saved_searches = isset($_COOKIE[$this->cookieName]) ? $this->unfilterAndUnserialize($_COOKIE[$this->cookieName]) : array();
		return $saved_searches;
	}

	private function serializeAndFilter($savedSearches)
	{
		return base64_encode(serialize($savedSearches));
	}
	private function unfilterAndUnserialize($savedSaerches)
	{
		if (is_null($savedSaerches))
		{
			return array();
		}
		return unserialize(base64_decode($savedSaerches));
	}

	public function getSearchCount()
	{
		$searches = $this->getSavedSearchesFromCookie();
		return (int) @count($searches);
	}
}

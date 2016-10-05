<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\lib;

class RedirectAfterLoginAction
{
	private $httpReferer;
	private $queryString;
	private $currentPageUri;

	public function setHttpReferer($httpReferer)
	{
		$this->httpReferer = $httpReferer;
	}

	public function setQueryString($queryString)
	{
		$this->queryString = $queryString;
	}

	public function setCurrentPageUri($currentPageUri)
	{
		$this->currentPageUri = $currentPageUri;
	}

	function perform()
	{
		$page_config = \App()->PageManager->getPageConfig($this->currentPageUri);

		if (isset($this->httpReferer) && !empty($this->httpReferer))
		{
			$redirect_url = $this->httpReferer;
			if (!empty($this->queryString)) $redirect_url .= "?" . $this->queryString;
		}
		elseif ($page_config->getMainContentModule() == 'users' && ($page_config->getMainContentFunction() == 'login' || $page_config->getMainContentFunction() == 'openid_oauth_login' || $page_config->getMainContentFunction() == 'registration'))
		{
			$redirect_url = \App()->SystemSettings['SiteUrl'];
		}
		else
		{
			$redirect_url = \App()->SystemSettings['SiteUrl'] . $this->currentPageUri;
			if (!empty($this->queryString)) $redirect_url .= "?" . $this->queryString;
		}

		throw new \lib\Http\RedirectException($redirect_url);
	}
}

?>

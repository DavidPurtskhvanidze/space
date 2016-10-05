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

namespace modules\classifieds\lib\Listing;


class DisplayHelper
{

	private $errors;
	private $listing_id;
	private $request;
	private $listing;
    private static $instance = null;

    static public function getInstance($request)
    {
        if (is_null(self::$instance))
            self::$instance = new DisplayHelper($request);
        return self::$instance;
    }

	private function __construct($request)
	{
		$this->request = $request;
		$this->defineListingId();
	}

	public function defineListingId()
	{
		if (!empty($this->listing_id)) return $this->listing_id;

		if (isset($this->request['passed_parameters_via_uri']))
        {
			$parameters_via_url = \App()->UrlParamProvider->getParams();
			$listing_id = isset($parameters_via_url[0]) ? $parameters_via_url[0] : null;
		}
        elseif (isset($this->request['listing_id']))
        {
			$listing_id = $this->request['listing_id'];

		}
        else
        {
			$listing_id = null;
		}

		$this->listing_id = $listing_id;
	}

	public function canDisplay()
	{
		$listing_id = $this->getListingId();

		if (is_null($listing_id))
        {
			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found'); //
			$this->errors['UNDEFINED_LISTING_ID'] = true;
		}
        elseif (is_null($this->listing = \App()->ListingManager->getObjectBySID($listing_id)))
        {
			if (\App()->SettingsFromDB->getSettingByName('display_default_response_on_listing_not_found_and_deactivated')) {
				header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found'); // no such listing
				$this->errors['WRONG_LISTING_ID_SPECIFIED'] = true;
			}
            else
				throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->SettingsFromDB->getSettingByName('redirect_uri_on_listing_not_found_and_deactivated'));
		} elseif (!$this->listing->isActive() && $this->listing->getUserSID() != \App()->UserManager->getCurrentUserSID())
        {
			if (\App()->SettingsFromDB->getSettingByName('display_default_response_on_listing_not_found_and_deactivated'))
            {
				header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden'); //
				$this->errors['LISTING_IS_NOT_ACTIVE'] = true;
			}
            else
				throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->SettingsFromDB->getSettingByName('redirect_uri_on_listing_not_found_and_deactivated'));
		}

		return empty($this->errors) ? true : false;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function getListing()
	{
		return $this->listing;
	}

    /**
     * @param null $listing_id
     */
    public function setListingId($listing_id)
    {
        $this->listing_id = $listing_id;
    }

    /**
     * @return mixed
     */
    public function getListingId()
    {
        return $this->listing_id;
    }
}

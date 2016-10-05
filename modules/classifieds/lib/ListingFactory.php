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


namespace modules\classifieds\lib;

class ListingFactory implements \core\IService
{
	/**
	 * @var \modules\classifieds\lib\Listing\Listing[]
	 */
	private $listingPrototypes = array();
	/**
	 * @var \modules\classifieds\lib\Listing\ListingDetails[]
	 */
	private $detailsPrototypes = array();

	/**
	 * @param array $listingData
	 * @param int $categorySid
	 * @return \modules\classifieds\lib\Listing\Listing
	 * @throws \Exception
	 */
	public function getListing($listingData, $categorySid)
	{
		if (is_null($categorySid)) throw new \Exception("Category is null");
		if (!isset($this->listingPrototypes[$categorySid])) $this->buildListingPrototype($categorySid);
		$listing = clone $this->listingPrototypes[$categorySid];
		if (!empty($listingData))
		{
			$listing->incorporateData($listingData);

            if (isset($listingData['listing_package_info']))
            {
                $listing->setListingPackageInfo($listingData['listing_package_info']);
            }

			if (isset($listingData['sid']))
			{
				$listing->setSid($listingData['sid']);
				$listing->addIdProperty($listingData['sid']);
                $listing->addUserProperty();
                $listing->details->addPicturesProperty();
			}
		}
		return $listing;
	}
	
	public function getListingsDetails($categorySid)
	{
		if (is_null($categorySid)) throw new \Exception("Category is null");
		if (!isset($this->detailsPrototypes[$categorySid])) $this->buildDetailsPrototype($categorySid);
		$details = clone $this->detailsPrototypes[$categorySid];
		return $details;
	}
	
	private function buildDetailsPrototype($categorySid)
	{
		$details= new Listing\ListingDetails();
		$details->setDetailsInfo($this->getDetailsMetadata($categorySid));
		$details->setCategoryManager(\App()->CategoryManager);
		$details->setMembershipPlanManager(\App()->MembershipPlanManager);
		$details->setUserManager(\App()->UserManager);
		$details->setListingPackageManager(\App()->ListingPackageManager);
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->setCategoryTreeAdapter(new Category\CategoryTreeAdapterForORM( \App()->CategoryTree ));
		$details->buildProperties();
		$this->detailsPrototypes[$categorySid] = $details;
	}

	private function buildListingPrototype($categorySid)
	{
		$listing = new Listing\Listing();
		$listing->setDetails($this->getListingsDetails($categorySid));
		$listing->setCategorySID($categorySid);
		$listing->addCategoryProperty();
		$listing->addCategoryNameProperty();
		$listing->addCategoryIDProperty();
		$listing->addActivationDateProperty();
		$listing->addExpirationDateProperty();
		$listing->addActiveProperty();
        $listing->addUsernameProperty();
		$listing->addIdProperty();
		$listing->addModerationStatusProperty();
		$listing->addNumberOfViewsProperty();
		$listing->addKeywordsProperty();
		$listing->setTemplateIdForStringRepresentation($categorySid);
		$listing->setTemplateContentForStringRepresentation(\App()->CategoryManager->getListingTemplateContentForStringRepresentation($categorySid));
		$listing->setTemplateLastModifiedForStringRepresentation(\App()->CategoryManager->getListingTemplateLastModifiedForStringRepresentation($categorySid));
		$listing->setTemplateIdForUrlSeoData($categorySid);
		$listing->setTemplateContentForUrlSeoData(\App()->CategoryManager->getListingTemplateContentForUrlSeoData($categorySid));
		$listing->setTemplateLastModifiedForUrlSeoData(\App()->CategoryManager->getListingTemplateLastModifiedForUrlSeoData($categorySid));
		
		$this->listingPrototypes[$categorySid] = $listing;
	}
	public function getListingFeaturesDetailsMetadata()
	{
		return \App()->ListingFeaturesManager->getListingFeaturesDetails();
	}
	
	public function getFeatureListingDetailsMetadata()
	{
		return \App()->ListingFeaturesManager->getFeatureListingDetails();
	}
	
	public function getCategoryDetailsMetadata($category_sid)
	{
		$details = \App()->ListingFieldManager->getListingFieldsInfoByCategory($category_sid);
		return $details;
	}	

	function getDetailsMetadata($category_sid)
	{
		$details = array_merge(
							Listing\ListingDetails::$system_details,
							$this->getListingFeaturesDetailsMetadata(), // fields that come from listings packages (youtube, highlighted, etc.)
							$this->getCategoryDetailsMetadata($category_sid), // fields related to listings type (including inherited)
							$this->getFeatureListingDetailsMetadata() // fields that come from listings packages (youtube_video_id)
							);
		return $details;
	}

	public function invalidateCacheForCategory($categorySid)
	{
		unset($this->listingPrototypes[$categorySid]);
		unset($this->detailsPrototypes[$categorySid]);
	}

	public function addPropertyToListingPrototypeForCategory($categorySid, $propertyInfo)
	{
		$details = $this->getListingsDetails($categorySid);
		$details->addProperty($propertyInfo);
		$this->detailsPrototypes[$categorySid] = $details;

		if (!isset($this->listingPrototypes[$categorySid]))
		{
			$this->buildListingPrototype($categorySid);
		}
		$listing = $this->listingPrototypes[$categorySid];
		$listing->setDetails($this->getListingsDetails($categorySid));
	}
}

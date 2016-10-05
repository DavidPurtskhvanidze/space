<?php
/**
 *
 *    Module: basket v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: basket-7.5.0-1
 *    Tag: tags/7.5.0-1@19771, 2016-06-17 13:18:56
 *
 *    This file is part of the 'basket' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\basket\lib;

class BasketItemManager extends \lib\ORM\ObjectManager implements \core\IService
{
	private $tableName = 'basket_baskets';
	
    public function getItemsByRequestGroupedByListing($request)
    {
		$search = $this->getSearch($request);
        $items = $search->getFoundObjectCollection();
        $result = [];
        /**
         * @var BasketItem $item
         */
	    foreach ($items as $item)
        {
	        $listingSid = $item->getPropertyValue('listing_sid');
            if (!isset($result[$listingSid]))
            {
				$listing = \App()->ListingManager->getListingBySid($item->getPropertyValue('listing_sid'));
                if (is_null($listing))
                {
                    $this->deleteItemBySid($item->getSID());
		            continue;
	            }
                $result[$listingSid]['listing'] = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($listing);
                $result[$listingSid]['paidListingPackageOptions'] = $this->getPaidPackageOptionsFromListing($listing);
                $result[$listingSid]['buyableListingPackageOptions'] = $result[$listingSid]['paidListingPackageOptions'];
				foreach ($result[$listingSid]['buyableListingPackageOptions'] as $optionId => $optionInfo)
				{
					if ((bool) $listing->getPropertyValue($optionId))
					{
						unset($result[$listingSid]['buyableListingPackageOptions'][$optionId]);
					}
				}
				$result[$listingSid]['options'] = [];
				$result[$listingSid]['totalOptionPrice'] = 0;
            }
			$optionId = $item->getPropertyValue('option_id');
            if ($optionId == 'activation')
            {
                $listingPackage = $listing->getPropertyValue('package');
				if ($listingPackage['price'] > 0)
				{
					$activationOption = array(
						'sid'	=> $item->getSid(),
						'isRemovable' => false,
						'name'	=> 'Activation',
						'id'	=> 'activation',
						'price'	=> $listingPackage['price']
					);
                    array_unshift($result[$listingSid]['options'], $activationOption);
				}
				$result[$listingSid]['totalOptionPrice'] += $listingPackage['price'];
			}
			else
			{
				unset($result[$listingSid]['buyableListingPackageOptions'][$optionId]);
				$result[$listingSid]['options'][] = array(
					'sid' => $item->getSid(),
					'isRemovable' => true,
					'name' => $result[$listingSid]['paidListingPackageOptions'][$optionId]['name'],
					'id' => $result[$listingSid]['paidListingPackageOptions'][$optionId]['id'],
					'price' => $result[$listingSid]['paidListingPackageOptions'][$optionId]['price']
				);
				$result[$listingSid]['totalOptionPrice'] += $result[$listingSid]['paidListingPackageOptions'][$optionId]['price'];
			}
		}
        return $result;
    }
	
	public function getItemsInfoByRequest($request)
	{
		$search = $this->getSearch($request);
        $items = $search->getFoundObjectCollection();
		
		$paidPackageOptions = [];
		$listingPackages = [];
        $result = [];

		/**
		 * @var BasketItem $item
		 */
		foreach ($items as $item)
        {
            $listingSid = $item->getPropertyValue('listing_sid');
            if (!isset($paidPackageOptions[$listingSid]))
            {
				$listing = \App()->ListingManager->getListingBySid($item->getPropertyValue('listing_sid'));
                $paidPackageOptions[$listingSid] = $this->getPaidPackageOptionsFromListing($listing);
	            $listingPackages[$listingSid] = $listing->getPropertyValue('package');
            }

			$optionId = $item->getPropertyValue('option_id');
	        $optionName = ($optionId == 'activation' ? 'Activation' : $paidPackageOptions[$listingSid][$optionId]['name']);
	        $price = ($optionId == 'activation' ? $listingPackages[$listingSid]['price'] : $paidPackageOptions[$listingSid][$optionId]['price']);
	        $result[$item->getSID()] = array
	        (
				'sid' => $item->getSID(),
				'listing_sid' => $listingSid,
				'option_id' => $optionId,
				'option_name' => $optionName,
				'price' => $price,
			);
		}
		return $result;
	}
    
	public function getBuyableOptionsByListingSid($listingSid)
	{
		$listing = \App()->ListingManager->getListingBySid($listingSid);
		$request = array('listing_sid' => array('equal' => $listingSid));
		$basketItems = $this->getItemsByRequestGroupedByListing($request);
		if (empty($basketItems))
		{
			$buyableOptions = $this->getPaidPackageOptionsFromListing($listing);
		}
		else
		{
			$buyableOptions = $basketItems[$listingSid]['buyableListingPackageOptions'];
		}
		return $buyableOptions;
	}
	
	private function getSearch($request)
	{
		$currentUserSid = \App()->UserManager->getCurrentUserSID();
		if (!$currentUserSid)
		{
			throw new Exception('USER_NOT_LOGGED_IN', 'users');
		}
		$request['user_sid']['equal'] = $currentUserSid;
        $search = new \lib\ORM\SearchEngine\Search();
        $search->setRequest($request);
		$search->setDB(\App()->DB);
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		$search->setRowMapper(new \modules\basket\lib\BasketItemToRowMapperAdapter($this));
		$search->setModelObject($this->getModelObject());
        $search->setPage(1);
        $search->setObjectsPerPage(100000);
        $search->setSortingFields(array('option_id' => 'ASC'));
		return $search;
	}
    
    private function getModelObject()
    {
        static $model = null;
		if (is_null($model))
		{
			$model = $this->createItem();
		}
		return $model;
    }

    public function createItem($info = [])
    {
		$item = new BasketItem();
		$item->setDetails($this->createItemDetails());
		$item->incorporateData($info);
		if (isset($info['sid']))
        {
            $item->setSid($info['sid']);
        }
		return $item;
	}		
	
	private function createItemDetails()
	{
		$details = new BasketItemDetails();
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
        $details->setDetailsInfo(BasketItemDetails::$system_details);
		$details->buildProperties();
		return $details;
	}
	
	private function getPaidPackageOptionsFromListing($listing)
	{
		return array_merge(
			\App()->ListingFeaturesManager->getPaidFeaturesByPackageInfo($listing->getPropertyValue('package')),
			\App()->AdditionalListingOptionManager->getPaidOptionsByListing($listing)
		);
	}

	public function deleteItemBySid($sid)
	{
		\App()->DB->query("DELETE FROM `{$this->tableName}` WHERE (`option_id` <> 'activation') AND (`sid` = ?n)", $sid);
	}

	public function deleteItemBySids(array $sids)
	{
		\App()->DB->query("DELETE FROM `{$this->tableName}` WHERE (`sid` IN (?l))", $sids);
	}
	
	public function deleteItemByListingSid($listingSid)
	{
		\App()->DB->query("DELETE FROM `{$this->tableName}` WHERE `listing_sid` = ?n", $listingSid);
	}

	public function deleteItemByListingSidsAndOptionIds(array $listingSids, array $freeOptionIds)
	{
		\App()->DB->query("DELETE FROM `{$this->tableName}` WHERE (`listing_sid` IN (?l)) AND (`option_id` IN (?l))", $listingSids, $freeOptionIds);
	}

	public function addItemsToBasket($listingSid, $userSid, $optionIds)
	{
		foreach ($optionIds as $optionId)
		{
			if ($this->doesBasketItemExist($listingSid, $optionId))
			{
				continue;
			}
			$item = $this->createItem(
				array(
					'user_sid' => $userSid,
					'listing_sid' => $listingSid,
					'option_id' => $optionId
				)
			);
			$this->saveObject($item);
		}
	}

	private function doesBasketItemExist($listingSid, $optionId)
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `{$this->tableName}` WHERE `listing_sid` = ?n AND `option_id` = ?s", $listingSid, $optionId) != 0;
	}

    public function getListingAmountInBasket()
    {
        $search = $this->getSearch([]);
        return count($search->getNumberOfObjectsFoundGroupedBy('listing_sid'));
    }
	
	public function getListingSidByOptionSid($optionSid)
	{
		return \App()->DB->getSingleValue("SELECT `listing_sid` FROM `{$this->tableName}` WHERE `sid` = ?s", $optionSid);		
	}
	
	public function getOptionIdByOptionSid($optionSid)
	{
		return \App()->DB->getSingleValue("SELECT `option_id` FROM `{$this->tableName}` WHERE `sid` = ?s", $optionSid);		
	}

	public function getListingSidsInBasketByUserSid($userSid)
	{
		return \App()->DB->column("SELECT DISTINCT `listing_sid` FROM `{$this->tableName}` WHERE `user_sid` = ?n", $userSid);
	}
}

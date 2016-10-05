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

/**
 * @property ListingDBManager dbManager
 */
class ListingManager extends \lib\ORM\ObjectManager implements \core\IService
{
	function init()
	{
		$this->dbManager = new \modules\classifieds\lib\Listing\ListingDBManager();
	}

	/**
	 * @param Listing $listing
	 * @return bool
	 */
	public function saveListing($listing)
	{
		if ($res = $this->dbManager->saveListing($listing))
		{
			$this->updateListingKeywords($listing->getSid());
		}
		return $res;
	}
	
	function getListingsNumberByUserSID($user_sid) {
		
		return $this->dbManager->getListingsNumberByUserSID($user_sid);
		
	}

	function getAllListingSIDs()
	{
		return $this->dbManager->getAllListingSIDs();
	}

	function getListingInfoBySID($listing_sid)
	{
		$listing_info = $this->dbManager->getListingInfoBySID($listing_sid);
		if (!empty($listing_info))
		{
			$listing_info['id'] = $listing_info['sid'];
			return $listing_info;
		}
		else
		{
			return $this->getListingDataSavedTemporary($listing_sid);
		}

	}

	/**
	 * @param $listing_sid
	 * @return Listing
	 */
	function getObjectBySID($listing_sid)
	{
		if ($this->doesListingExist($listing_sid))
		{
			$listing_info = $this->getListingInfoBySID($listing_sid);
			$listing = \App()->ListingFactory->getListing($listing_info, $listing_info['category_sid']);
			$package_info = \App()->ListingPackageManager->getPackageInfoByListingSID($listing_sid);
			$listing->setListingPackageInfo($package_info);
			return $listing;
		}
		else
		{
			return $this->getListingSavedTemporary($listing_sid);
		}
	}

	function getActiveListingsByUserSID($user_sid) {

		$active_listings_sid = $this->dbManager->getActiveListingsSIDByUserSID($user_sid);

		$active_listings = [];

		foreach ($active_listings_sid as $active_listing_sid) {

			$active_listings[] = $this->getObjectBySID($active_listing_sid);

		}

		return $active_listings;

	}

	function getActiveListingNumberByUserSID($user_sid)
	{
		$active_listings = $this->getActiveListingsByUserSID($user_sid);

		return count($active_listings);
	}

	function getListingsByUserSID($user_sid) {
		
		$listings_sid = $this->dbManager->getListingsSIDByUserSID($user_sid);
		
		$listings = [];
		
		foreach ($listings_sid as $listing_sid) {
			
			$listings[] = $this->getObjectBySID($listing_sid);
			
		}

		return $listings;
	}
	
	function activateListingBySID($listing_sid)
	{
		$currentActivationDate = $this->getListingActivationDateBySid($listing_sid);

		if ($this->dbManager->activateListingBySID($listing_sid))
		{
			$expDate = $this->getListingExpirationDateBySid($listing_sid);
			$listingIsExpired = strtotime($expDate) < strtotime("now");
			if (is_null($expDate) || $listingIsExpired)
			{
				$this->setListingActivationDateBySid($listing_sid);
				$this->setListingExpirationDateBySid($listing_sid);

				if (is_null($currentActivationDate))
				{
					$this->setListingFirstActivationDateBySid($listing_sid);
					
					$onListingFirstActivationActions = new \core\ExtensionPoint('modules\classifieds\IOnListingFirstActivationAction');
					foreach ($onListingFirstActivationActions as $onListingFirstActivationAction)
					{
						$onListingFirstActivationAction->setListingSid($listing_sid);
						$onListingFirstActivationAction->perform();
					}
				}
			}
			return true;
		}
		
		return false;
	}
	
	function setListingExpirationDateBySid($listing_sid) {
		
		return $this->dbManager->setListingExpirationDateBySid($listing_sid);
		
	}
	
	
	function deleteListingBySID($listing_sid)
	{
		return $this->dbManager->deleteListingBySID($listing_sid);
	}
	
	function deactivateListingBySID($listing_sid) {
		
		return $this->dbManager->deactivateListingBySID($listing_sid);
		
	}
	
	function getPropertyByPropertyName($property_name, $category_sid = 0)
	{
		$property_info = null;
		$listing_details = \App()->ListingFactory->getDetailsMetadata($category_sid);
		foreach ($listing_details as $detail)
		{
			if($detail['id'] === $property_name)
			{
				$property_info = $detail;
				break;
			}
		}
		if(is_null($property_info)) return null;
		$property_info['table_name'] = 'classifieds_listings';
		$objectProperty = \App()->OrmObjectFactory->createObjectProperty($property_info);
		return $objectProperty;
	}

	function propertyIsCommon($property_name)
	{
		$common_property = $this->getPropertyByPropertyName($property_name);

		return !empty($common_property);
	}

	function propertyIsSystem($property_name)
	{
		$system_properties = \App()->DB->query("SHOW COLUMNS FROM `classifieds_listings`");

		foreach ($system_properties as $property)
		{
			if ($property['Field'] == $property_name)
				return true;
		}

		return false;
	}
	
	function getAllListingProperties($category_sid = null)
	{		
		$systemProperties = array
		(
			array('id' => 'id', 'caption' => 'ID'),
			array('id' => 'category', 'caption' => 'Category'),
			array('id' => 'username', 'caption' => 'Username'),
			array('id' => 'active', 'caption' => 'Is Active'),
			array('id' => 'keywords', 'caption' => 'Keywords'),
			array('id' => 'views', 'caption' => '# of Views'),
			array('id' => 'pictures', 'caption' => 'Pictures'),
			array('id' => 'activation_date', 'caption' => 'Activation Date'),
			array('id' => 'expiration_date', 'caption' => 'Expiration Date'),
		);
		$userDefinedProperties = \App()->ListingFieldManager->getListingFieldsInfoByCategory($category_sid);
				
		return array_merge($systemProperties, $userDefinedProperties);
	}
	
	function getExpiredListingsSID() {
		
		return $this->dbManager->getExpiredListingsSID();
		
	}
	function getListingsSidExpiredBeetwen($dateFrom, $dateTo)
	{
		return array_map(create_function('$row', 'return $row["sid"];'), \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `expiration_date` > ?s AND `expiration_date` <= ?s", $dateFrom, $dateTo));
	}
	
	function getUserSIDByListingSID($listing_sid) {
		
		return $this->dbManager->getUserSIDByListingSID($listing_sid);
		
	}

	function createTemplateStructureForListing($listing)
	{
		$listing_info = parent::getObjectInfo($listing);
		$category_info = \App()->CategoryManager->getInfoBySID($listing_info['system']['category_sid']);
		$user 		= \App()->UserManager->getObjectBySID($listing_info['system']['user_sid']);
		$user_info 	= !empty($user) ? \App()->UserManager->createTemplateStructureForUser($user) : null;
		$package_info = \App()->ListingPackageManager->createTemplateStructureForPackageByListingSID($listing->getSID());
		$structure = array
        (
			'id'				=> $listing_info['system']['id'],
			'type'				=> array
									(
										'sid' 		=> $category_info['sid'],
										'id' 		=> $category_info['id'],
										'caption' 	=> $category_info['name']
									),
			'user'				=> $user_info,
			'activation_date'	=> $listing_info['system']['activation_date'],
			'expiration_date'	=> $listing_info['system']['expiration_date'],
			'views'				=> $listing_info['system']['views'],
			'active'			=> $listing_info['system']['active'],
			'package'			=> $package_info,
			'number_of_pictures'=> isset($listing_info['user_defined']['pictures']) ? count($listing_info['user_defined']['pictures']) : 0,
			'moderation_status' => $listing_info['system']['moderation_status'],
            'pictures'          => $listing_info['system']['pictures'],
        );
        
        foreach (array_keys($listing_info['user_defined']) as $index) if (!isset($structure[$index])) $structure[$index] = $listing_info['user_defined'][$index];
        return $structure;
	}

	function incrementViewsCounterForListing($listing_id)
	{
		$listingViewsCount = \App()->DB->getSingleValue("SELECT `views` FROM `classifieds_listings` WHERE `sid`=?n", $listing_id);
		if (!is_null($listingViewsCount))
			$listing_views = $listingViewsCount;
		else
			return false;
		return \App()->DB->query("UPDATE `classifieds_listings` SET `views`=?n WHERE `sid`=?n", $listing_views + 1, $listing_id);
	}

	function getListingSIDByID($id) {
		
		return $id;
	}

	function getSIDCollectionByTypes($types) {
		$sid_collection = [];
		$sid_collection_info = $this->dbManager->getSIDCollectionByTypes($types);
		foreach($sid_collection_info as $sid_info)
			$sid_collection[] = $sid_info['sid'];
		return $sid_collection;
	}
	
	function getListingCounts()
	{
		return \App()->DB->query('SELECT `category_sid`, COUNT(*) AS `listing_count` FROM `classifieds_listings` GROUP BY `category_sid`');
	}
	
	function approveListingBySID($listingSid)
	{
		$this->activateListingBySID($listingSid);
		$this->setModerationStatus($listingSid, 'APPROVED');

		$actions = new \core\ExtensionPoint('modules\classifieds\lib\Listing\IAfterListingApprovedAction');
		foreach ($actions as $action)
		{
			/**
			 * @var IAfterListingApprovedAction $action
			 */
			$action->setListingSid($listingSid);
			$action->perform();
		}
	}
	
	function setModerationStatus($listingSid, $moderationStatus)
	{
		return \App()->DB->query("UPDATE `classifieds_listings` SET `moderation_status` = ?s WHERE `sid` = ?n", $moderationStatus, $listingSid);
	}
	
	function rejectListingBySID($listing_id)
	{
		$this->setActiveStatus($listing_id, 0);
		$this->setModerationStatus($listing_id, 'REJECTED');
	}
	
	function userActivateListingBySID($listing_id)
	{
		if ($this->getModerationStatus($listing_id) == 'APPROVED')
		{
			$this->approveListingBySID($listing_id);
		}
		elseif ($this->getModerationStatus($listing_id) == '' || is_null($this->getModerationStatus($listing_id)))
		{
			$isUserTrusted = \App()->UserManager->isUserTrusted($this->getUserSIDByListingSID($listing_id));
			if ($isUserTrusted)
			{
				$this->approveListingBySID($listing_id);
			}
			else
			{
				$this->setModerationStatus($listing_id, 'PENDING');
			}
		}
	}
	
	function getModerationStatus($listingSid)
	{
		$res = \App()->DB->getSingleValue("SELECT `moderation_status` FROM `classifieds_listings` WHERE `sid` = ?n", $listingSid);
		return $res;
	}
	
	function updateListingStatusAfterModified($listing_id)
	{
		$active_status = $this->getActiveStatus($listing_id);
		$moderation_status = $this->getModerationStatus($listing_id);
		$isOwnerTrustedUser = \App()->UserManager->isUserTrusted($this->getUserSIDByListingSID($listing_id));
		
		if ($active_status == 0 && $moderation_status == 'APPROVED')
		{
			if (!$isOwnerTrustedUser)
			{
				$this->setModerationStatus($listing_id, null);
				$this->setActiveStatus($listing_id, 0);
			}
		}
		elseif ($active_status == 0 && $moderation_status == 'REJECTED')
		{
			$this->setModerationStatus($listing_id, 'PENDING');
			$this->setActiveStatus($listing_id, 0);
		}
		elseif ($active_status == 1 && $moderation_status == 'APPROVED')
		{
			if (!$isOwnerTrustedUser)
			{
				$this->setModerationStatus($listing_id, 'PENDING');
				$this->setActiveStatus($listing_id, 0);
			}
		}
	}
	
	function getActiveStatus($listingSid)
	{
		$res = \App()->DB->getSingleValue("SELECT `active` FROM `classifieds_listings` WHERE `sid` = ?n", $listingSid);
		return $res;
	}
	
	function setActiveStatus($listingSid, $status)
	{
		return \App()->DB->query("UPDATE `classifieds_listings` SET `active` = ?n WHERE `sid` = ?n", $status, $listingSid);
	}
	
	function getListingExpirationDateBySid($listingSid)
	{
		$res = \App()->DB->getSingleValue("SELECT `expiration_date` FROM `classifieds_listings` WHERE `sid` = ?n", $listingSid);
		return $res;
	}
	
	function setListingActivationDateBySid($listingSid)
	{
		return \App()->DB->query("UPDATE `classifieds_listings` SET `activation_date` = NOW() WHERE `sid` = ?n", $listingSid);
	}
	
	function getListingActivationDateBySid($listingSid)
	{
		$res = \App()->DB->getSingleValue("SELECT `activation_date` FROM `classifieds_listings` WHERE `sid` = ?n", $listingSid);
		return $res;
	}
	
	function setListingFirstActivationDateBySid($listingSid)
	{
		return \App()->DB->query("UPDATE `classifieds_listings` SET `first_activation_date` = NOW() WHERE `sid` = ?n", $listingSid);
	}

	function getListingFirstActivationDateBySid($listingSid)
	{
		$res = \App()->DB->getSingleValue("SELECT `first_activation_date` FROM `classifieds_listings` WHERE `sid` = ?n", $listingSid);
		return $res;
	}
	
	function getListingsSIDByUserSID($userSid)
	{
		return $this->dbManager->getListingsSIDByUserSID($userSid);
	}
	function deactivateListings($listingsId)
	{
		return \App()->DB->query("UPDATE `classifieds_listings` SET `active` = 0 WHERE `sid` IN (?l)", $listingsId);
	}
	function getInactiveNotExpiredListingsIdByUserSid($userSid)
	{
		$res = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `active` = 0 AND `expiration_date` > NOW() AND `user_sid` = ?n", $userSid);
		$sids = array_map(create_function('$row', 'return $row["sid"];'), $res);
		return $sids;
	}
	public function getListingsInfoBySidCollection($listingSids)
	{
		return $this->dbManager->getListingsInfoBySidCollection($listingSids);
	}
	public function onDeleteField($listing_field_info)
	{
        $field = \App()->ListingFieldManager->getFieldBySID($listing_field_info['sid']);
        $columDefinition = \App()->ListingFieldManager->getColumnDefinitionForField($field);
		if (!is_null($columDefinition))
        {
            $listingsSids = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `{$listing_field_info['id']}` IS NOT NULL AND `{$listing_field_info['id']}` != ''");
            $listingsSids = array_map(create_function('$d', 'return $d["sid"];'), $listingsSids);
            $this->addListingsIdsForKeywordsUpdating($listingsSids);
        }
	}
	public function onDeleteTreeItem($treeItemInfo)
	{
		$listingsSid = $this->getListingsSidsInvolvedInTreeItemModification($treeItemInfo);
		if (empty($listingsSid)) return;
		$fieldInfo = \App()->ListingFieldManager->getInfoBySID($treeItemInfo['field_sid']);
		\App()->DB->query("UPDATE `classifieds_listings` SET `{$fieldInfo['id']}` = NULL WHERE `sid` IN (?l)", $listingsSid);
		$this->addListingsIdsForKeywordsUpdating($listingsSid);
	}
	public function onDeleteTreeItemsBySIDs($fieldSID, array $treeItemsSIDs)
	{
		$listingsSids = $this->getListingsSIDsInvolvedInTreeItemsModificationBySID($fieldSID, $treeItemsSIDs);
        if (empty($listingsSids)) return;
		$fieldInfo = \App()->ListingFieldManager->getInfoBySID($fieldSID);
		\App()->DB->query("UPDATE `classifieds_listings` SET `{$fieldInfo['id']}` = NULL WHERE `sid` IN (?l)", $listingsSids);
        $this->addListingsIdsForKeywordsUpdating($listingsSids);
	}
	public function onChangeTreeItem($treeItemInfo)
	{
		$this->addListingsIdsForKeywordsUpdating($this->getListingsSidsInvolvedInTreeItemModification($treeItemInfo));
	}
	public function onDeleteLocation($locationName)
	{
		$this->onChangeLocation(null, $locationName);
	}
	public function onChangeLocation($locationNewName, $locationOldName)
	{
		if ($locationNewName == $locationOldName) return;
		$geoFields = \App()->ListingFieldManager->getFieldsInfoByType('geo');
		$listingsIdsForKeywordsUpdating = [];
		foreach ($geoFields as $fieldInfo)
		{
			$listingsIds = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `{$fieldInfo['id']}` = ?s", $locationOldName);
			$listingsIds = array_map(create_function('$d', 'return $d["sid"];'), $listingsIds);
			if (empty($listingsIds)) continue;
			\App()->DB->query("UPDATE `classifieds_listings` SET `{$fieldInfo['id']}` = ?s WHERE `sid` IN (?l)", $locationNewName, $listingsIds);
			$listingsIdsForKeywordsUpdating = array_merge($listingsIdsForKeywordsUpdating, $listingsIds);
		}
		$this->addListingsIdsForKeywordsUpdating($listingsIdsForKeywordsUpdating);
	}
	private function getListingsSidsInvolvedInTreeItemModification($treeItemInfo)
	{
		$fieldInfo = \App()->ListingFieldManager->getInfoBySID($treeItemInfo['field_sid']);
		$branch = \App()->ListingFieldTreeManager->getChildrenSIDBySID($treeItemInfo['field_sid'],$treeItemInfo['sid']);
		$branch[] = $treeItemInfo['sid'];
		$res = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `{$fieldInfo['id']}` IN (?l)", $branch);
		return array_map(create_function('$v', 'return $v["sid"];'), $res);
	}
	private function getListingsSIDsInvolvedInTreeItemsModificationBySID($fieldSid, array $treeItemSIDs)
	{
		$fieldInfo = \App()->ListingFieldManager->getInfoBySID($fieldSid);
        $totalBranch = [];
        foreach ($treeItemSIDs as $treeItemSID)
        {
            $branch = \App()->ListingFieldTreeManager->getChildrenSIDBySID($fieldSid, $treeItemSID);
            $branch[] = $treeItemSID;
            $totalBranch = array_merge($totalBranch, $branch);
        }
		$res = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `{$fieldInfo['id']}` IN (?l)", $totalBranch);
		return array_map(create_function('$v', 'return $v["sid"];'), $res);
	}
	public function onChangeListItem($listItem)
	{
		$fieldInfo = \App()->ListingFieldManager->getInfoBySID($listItem->getFieldSID());
		$res = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `{$fieldInfo['id']}` = ?n", $listItem->getSID());
		$listingsSid = array_map(create_function('$v', 'return $v["sid"];'), $res);
		array_walk($listingsSid, array($this, 'updateListingKeywords'));
	}
	public function onDeleteListItem($listItem)
	{
		$fieldInfo = \App()->ListingFieldManager->getInfoBySID($listItem->getFieldSID());
		$res = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `{$fieldInfo['id']}` = ?n", $listItem->getSID());
		$listingsSid = array_map(create_function('$v', 'return $v["sid"];'), $res);
		\App()->DB->query("UPDATE `classifieds_listings` SET `{$fieldInfo['id']}` = NULL WHERE `{$fieldInfo['id']}` = ?n", $listItem->getSID());
		array_walk($listingsSid, array($this, 'updateListingKeywords'));
	}
	public function onDeleteAllListItemsByFieldSID($fieldSID)
	{
		$fieldInfo = \App()->ListingFieldManager->getInfoBySID($fieldSID);
        $res = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `{$fieldInfo['id']}` IS NOT NULL");
        $listingsSid = array_map(create_function('$v', 'return $v["sid"];'), $res);
        \App()->DB->query("UPDATE `classifieds_listings` SET `{$fieldInfo['id']}` = NULL");
		array_walk($listingsSid, array($this, 'updateListingKeywords'));
	}
	public function onDeleteSelectedListItemsBySID(array $listItemsSIDs, $fieldSID)
	{
        $fieldInfo = \App()->ListingFieldManager->getInfoBySID($fieldSID);
        $res = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `{$fieldInfo['id']}` IN (?l)", $listItemsSIDs);
        $listingsSid = array_map(create_function('$v', 'return $v["sid"];'), $res);
        \App()->DB->query("UPDATE `classifieds_listings` SET `{$fieldInfo['id']}` = NULL WHERE `{$fieldInfo['id']}` IN (?l)", $listItemsSIDs);
		array_walk($listingsSid, array($this, 'updateListingKeywords'));
	}
	public function clearListingFieldRank($column_name, $rank)
	{
		$xor_value = pow(2, $rank);
		\App()->DB->query("UPDATE `classifieds_listings` SET `{$column_name}` = `{$column_name}` ^ {$xor_value} WHERE `{$column_name}` & {$xor_value};");
	}
	public function updateListingKeywords($listingSid)
	{
		$listing = $this->getObjectBySID($listingSid);
		return \App()->DB->query("UPDATE `classifieds_listings` SET `keywords` = ?s WHERE `sid` = ?n", $listing->getKeywords(), $listing->getSid());
	}
	public function getCategorySidByListingSid($listingSid)
	{
		return \App()->DB->getSingleValue("SELECT `category_sid` FROM `classifieds_listings` WHERE `sid` = ?n", $listingSid);
	}
	public function doesListingExist($listingSid)
	{
		$count = \App()->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_listings` WHERE `sid` = ?n", $listingSid);
		return $count == 1;
	}
	public function listingInfoI18N($listingInfo)
	{
		// thousands separator is taken as empty string, see ticket:2026:comment:2
		$floatFormatter = \App()->ObjectMother->createFloatFormatter("", \App()->I18N->getContext()->getDecimalPoint());
		$floatFields = \App()->ListingFieldManager->getFieldsInfoByType('float');
		$decimalFields = \App()->ListingFieldManager->getFieldsInfoByType('decimal');
		$moneyFields = \App()->ListingFieldManager->getFieldsInfoByType('money');
		$fields = array_merge($floatFields, $decimalFields, $moneyFields);
		foreach ($fields as $field)
		{
			if (!isset($listingInfo[$field['id']])) continue;
			$floatFormatter->setDecimals($field['signs_num']);
			$listingInfo[$field['id']] = $floatFormatter->getOutput($listingInfo[$field['id']]);
		}
		$dateFields = \App()->ListingFieldManager->getFieldsInfoByType('date');
		foreach ($dateFields as $field)
		{
			$listingInfo[$field['id']] = \App()->I18N->getDate($listingInfo[$field['id']]);
		}
		return $listingInfo;
	}
	public function getListingsIdsForKeywordsUpdating()
	{
		$ids = \App()->SettingsFromDB->getSettingByName('listings_to_update_keywords');
		return empty($ids) ? [] : explode(',', $ids);
	}
	public function setListingsIdsForKeywordsUpdating($ids)
	{
		return \App()->SettingsFromDB->updateSetting('listings_to_update_keywords', join(",", $ids));
	}
	public function addListingsIdsForKeywordsUpdating($ids)
	{
		$this->setListingsIdsForKeywordsUpdating(array_unique(array_merge($this->getListingsIdsForKeywordsUpdating(), $ids)));
	}

	public function getActiveListingsCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_listings` WHERE `active` = 1");
	}
	public function getListingsWaitingApprovalCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_listings` WHERE `moderation_status` = 'PENDING'");
	}
	public function getListingViewsCount()
	{
		return \App()->DB->getSingleValue("SELECT SUM(`views`) FROM `classifieds_listings`");
	}
	public function getListingsCountForLastDay()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_listings` WHERE `activation_date` >= CURDATE() - INTERVAL 1 DAY");
	}
	public function getListingsCountForLastWeek()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_listings` WHERE `activation_date` >= CURDATE() - INTERVAL 7 DAY ");
	}
	public function getListingsCountForLastMonth()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_listings` WHERE `activation_date` >= CURDATE() - INTERVAL 1 MONTH");
	}
	public function getListingsCountByUserSID($userSID)
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_listings` WHERE `user_sid` = ?n", $userSID);
	}
    /**
     * @param $sid
     * @return Listing|null
     */
	public function getListingBySid($sid)
    {
        $info = $this->getListingInfoBySID($sid);
        return !is_null($info) ? \App()->ListingFactory->getListing($info, $info['category_sid']) : null;
    }

	/**
	 * @param Listing $listing
	 */
	public function userActivateListing($listing)
	{
		$this->userActivateListingBySID($listing->getSid());
		if ($this->getActiveStatus($listing->getSid()))
		{
			$listing->setActive(true);
		}
	}

	public function fetchAutocompleteOptionsForListingKeywords($keyword, $maxRows, $formFieldValues=[], $preselectFieldNames=[])
	{
		foreach ($formFieldValues as $fieldName => $value)
		{
			if (!in_array($fieldName, $preselectFieldNames))
			{
				unset($formFieldValues[$fieldName]);
			}
		}
		$formFieldValues['keywords']['like'] = $keyword;
		
		$searchListingsHelper = new \modules\classifieds\lib\SearchListingsHelper();
		$modelListing = \App()->ListingFactory->getListing([], $searchListingsHelper->getCategorySidFromArray($formFieldValues));
		
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setObjectsPerPage($maxRows * 3);
		$search->setRequest($formFieldValues);
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
		$search->setModelObject($modelListing);
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		
		$searchResultCollection = $search->getFoundObjectCollection();
		// Here we extracting words matching keyword
		$result = [];
		foreach($searchResultCollection as $listing)
		{
			preg_match_all('/\p{L}*' . preg_quote($keyword) . '\p{L}*/iu', $listing->getPropertyValue('keywords'), $matches, PREG_PATTERN_ORDER);
			if (!empty($matches))
			{
				foreach ($matches[0] as $value)
				{
					$result[] = $value;
				}
			}
		}
		// Case insensitive array_unique
		$callbackCache = [];
		$result = array_filter(
			$result,
			function ($value) use (&$callbackCache, $maxRows) {
				if (in_array(strtolower($value), $callbackCache))
				{
					return false;
				}
				$callbackCache[] = strtolower($value);
				return (count($callbackCache) <= $maxRows) ? true : false;
			}
		);
		// building structured result
		$result = array_map(
			function($value)
			{
				return array(
					'value' => $value,
					'label' => $value,
				);
			},
			$result
		);

		return $result;
	}

	public function reserveSidForListing()
	{
		$sidForListing = \App()->DB->query("INSERT INTO `classifieds_listings`() VALUES()");
		\App()->DB->query("DELETE FROM `classifieds_listings` WHERE `sid` = ?n", $sidForListing);
		return $sidForListing;
	}

	/**
	 * Save listing base data (sid, user_sid, package_info, category_sid) in the session
	 *
	 * This method was developed for the uploading pictures during listing add.
	 * As listing does not yet exist in the db we save it in the session temporary.
	 *
	 * @param Listing $listing
	 * @param $listingData
	 */
	private function saveListingTemporary($listing, $listingData)
	{
		$listingData['sid'] = $listing->getSID();
		$listingData['category_sid'] = $listing->getCategorySID();
		$listingData['user_sid'] = $listing->getUserSID();
		$listingData['listing_package_info'] = $listing->getListingPackageInfo();

		\App()->Session->getContainer('TemporarySavedListings')->setValue($listing->getSID(), serialize($listingData));
	}

	/**
	 * @param integer $listingSid
	 * @return Listing
	 */
	private function getListingSavedTemporary($listingSid)
	{
		$listingData = \App()->Session->getContainer('TemporarySavedListings')->getValue($listingSid);
		if (is_null($listingData))
		{
			return null;
		}
		$listingData = unserialize($listingData);
		$listing = \App()->ObjectMother->getListingFactory()->getListing($listingData, $listingData['category_sid']);
		$listing->setSID($listingSid);
		$listing->setUserSID($listingData['user_sid']);
		$listing->setListingPackageInfo($listingData['listing_package_info']);
		return $listing;
	}

	private function getListingDataSavedTemporary($listingSid)
	{
		$listingData = \App()->Session->getContainer('TemporarySavedListings')->getValue($listingSid);
		if (is_null($listingData))
		{
			return null;
		}
		return unserialize($listingData);
	}

	/**
	 * @param Listing $listing
	 */
	public function prepareReservedPlaceForListing($listing)
	{
		\App()->DB->query("INSERT INTO `classifieds_listings`(`sid`) VALUES(?n)", $listing->getSID());
	}

	/**
	 * Update listing info in the db if it exists or in the session
	 *
	 * @param Listing $listing
	 * @param array $data
	 */
	public function updateListingPartially($listing, $data)
	{
		if ($this->doesListingExist($listing->getSID()))
		{
			$propertyIds = array_keys($listing->getProperties());
			$propertiesToExclude = array_diff($propertyIds, array_keys($data));
			array_walk($propertiesToExclude, array($listing, 'deleteProperty'));
			foreach ($data as $propertyId => $value)
			{
				$listing->setPropertyValue($propertyId, $value);
			}
			$this->saveListing($listing);
		}
		else
		{
			foreach ($data as $propertyId => $value)
			{
				$data[$propertyId] = $value;
			}
			$this->saveListingTemporary($listing, $data);
		}
	}

	public function getSearch($request = [], $categorySid = 0)
	{
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setRequest($request);
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
        $search->setWith(['images', 'user']);
		$search->setModelObject(\App()->ListingFactory->getListing([], $categorySid));
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		return $search;

	}
}

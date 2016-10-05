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

class Listing extends \lib\ORM\Object
{
    var $category_sid = 0;
    var $listing_package_info = null;
    var $user_sid;
    var $active;
    var $activation_date;
    var $expiration_date;
    var $number_of_views;
    var $moderation_status;

    public function user()
    {
        return $this->belongsTo('users_users', 'user_sid');
    }

    public function images()
    {
        return $this->hasMany('classifieds_listings_pictures', 'listing_sid', '`order`');
    }

    public function package()
    {
        return $this->hasMany('membership_plan_listing_packages', 'listing_sid');
    }

    function setCategorySID($category_sid)
    {
        $this->category_sid = $category_sid;
    }

    public function incorporateData($listing_info)
    {
        $this->details->incorporateData($listing_info);
        $this->active = isset($listing_info['active']) ? (bool)$listing_info['active'] : false;
        $this->user_sid = isset($listing_info['user_sid']) ? $listing_info['user_sid'] : 0;
        $this->activation_date = isset($listing_info['activation_date']) ? $listing_info['activation_date'] : null;
        $this->expiration_date = isset($listing_info['expiration_date']) ? $listing_info['expiration_date'] : null;
        $this->number_of_views = isset($listing_info['views']) ? $listing_info['views'] : null;
        $this->moderation_status = isset($listing_info['moderation_status']) ? $listing_info['moderation_status'] : null;
    }

    function getActivationDate()
    {
        return $this->activation_date;
    }

    function getNumberOfViews()
    {
        return $this->number_of_views;
    }

    function getCategorySID()
    {
        return $this->category_sid;
    }

    function setUserSID($user_sid)
    {
        $this->user_sid = $user_sid;
    }

    function getUserSID()
    {
        return $this->user_sid;
    }

    function setListingPackageInfo($listing_package_info)
    {
        $this->listing_package_info = $listing_package_info;
        $this->details->setListingPackageInfo($listing_package_info);
    }

    function getListingPackageInfo()
    {
        return $this->listing_package_info;
    }

    function isActive()
    {
        return $this->active;
    }

    function setActive($active)
    {
        $this->active = (bool)$active;
    }

    function getKeywords()
    {
        $properties = $this->details->getProperties();
        $notRelevanceProperties = array('sid', 'user_sid', 'activation_date', 'type', 'category_sid', 'listing_package', 'expiration_date', 'active', 'id', 'moderation_status', 'views', 'package', 'pictures', 'user', 'username', 'keywords', 'feature_featured', 'feature_highlighted', 'feature_slideshow', 'feature_youtube', 'feature_sponsored', 'meta_keywords', 'meta_description', 'page_title');
        $keywords = '';
        foreach ($properties as $id => $p) {
            if (!in_array($id, $notRelevanceProperties)) {
                $keywords .= " " . $p->getKeywordValue();
            }
        }
        $keywords = strip_tags($keywords);
        $keywords = preg_replace("/\s+/", " ", trim($keywords));
        return $keywords;
    }

    function addActiveProperty($active = null)
    {
        if (empty($active)) $active = $this->isActive();
        return $this->details->addActiveProperty($active);
    }

    function addUsernameProperty($username = null)
    {
        return $this->details->addUsernameProperty($username);
    }

    function addIDProperty($id = null)
    {
        return $this->details->addIDProperty($id);
    }

    function addCategoryIDProperty($type_id = null)
    {
        return $this->details->addCategoryIDProperty($type_id);
    }

    function addKeywordsProperty($keywords = null)
    {
        return $this->details->addKeywordsProperty($keywords);
    }


    function addPicturesProperty()
    {
        return $this->details->addPicturesProperty();
    }

    function addActivationDateProperty($activation_date = null)
    {
        return $this->details->addActivationDateProperty($activation_date);
    }

    function addExpirationDateProperty($expiration_date = null)
    {
        return $this->details->addExpirationDateProperty($expiration_date);
    }

    function addNumberOfViewsProperty($number_of_views = null)
    {
        return $this->details->addNumberOfViewsProperty($number_of_views);
    }

    function isPropertySetOnAllListings($listings, $sorting_field)
    {
        foreach ($listings as $key => $val) {
            $listing = $listings[$key];
            $isPropertySet = $listing->propertyIsSet($sorting_field);
            if (!$isPropertySet) return false;
        }
        return true;
    }

    function addUserProperty()
    {
        $this->details->addUserProperty();
    }

    function addPackageProperty()
    {
        $this->details->addPackageProperty($this->getSid());
    }

    function addCategoryProperty()
    {
        $this->details->addCategoryProperty($this->category_sid);
    }

    function addCategoryNameProperty()
    {
        $this->addProperty
        (
            array
            (
                'id' => 'category',
                'type' => 'string',
                'is_system' => true,
                'table_name' => 'classifieds_categories',
                'column_name' => 'name',
                'join_condition' => array('key_column' => 'category_sid', 'foriegn_column' => 'sid'),
            )
        );
    }

    function toArray($decorator = null)
    {
        $properties = parent::toArray($decorator);
        $properties['number_of_pictures'] = count($properties['pictures']);
        $properties['id'] = $properties['sid'];
        return $properties;
    }

    function addModerationStatusProperty()
    {
        $propertyInfo = array
        (
            'id' => 'moderation_status',
            'caption' => 'Moderation Status',
            'type' => 'list',
            'length' => '',
            'is_required' => false,
            'is_system' => true,
            'list_values' => array
            (
                array('id' => 'PENDING', 'caption' => 'Pending'),
                array('id' => 'APPROVED', 'caption' => 'Approved'),
                array('id' => 'REJECTED', 'caption' => 'Rejected'),
            ),
            'value' => $this->moderation_status
        );
        $this->addProperty($propertyInfo);
    }

    function isExpired()
    {
        if ($this->isNeverActivatedBefore()) return false;
        $now = strtotime("now");
        $expiration_date = strtotime($this->expiration_date);
        return $expiration_date < $now;
    }

    function isNeverActivatedBefore()
    {
        return is_null($this->expiration_date);
    }

    function addLastUserIpProperty($ipValue)
    {
        return $this->details->addLastUserIpProperty($ipValue);
    }

    function getModerationStatus()
    {
        return $this->moderation_status;
    }

    public function __clone()
    {
        $this->details = clone $this->details;
    }

    public function addFirstActivationDateProperty($date = null)
    {
        return $this->addProperty(array
        (
            'id' => 'first_activation_date',
            'type' => 'date',
            'value' => $date,
        ));
    }

    public function isPendingApproval()
    {
        return strcasecmp($this->moderation_status, "PENDING") == 0;
    }

    public function isRejected()
    {
        return strcasecmp($this->moderation_status, "REJECTED") == 0;
    }

    public function getValueForEncodingToJson()
    {
        $valueForEncodingToJson = parent::getValueForEncodingToJson();
        $arrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this);
        $valueForEncodingToJson['listing_caption'] = array
        (
            'caption' => 'Listing Caption',
            'value' => strip_tags((string)$arrayAdapter)
        );
        $valueForEncodingToJson['category'] = $valueForEncodingToJson['type'];
        unset($valueForEncodingToJson['type']);
        return $valueForEncodingToJson;
    }
}

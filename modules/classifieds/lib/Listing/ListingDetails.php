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

class ListingDetails extends \lib\ORM\ObjectDetails
{
    var $properties;
    protected $tableName = 'classifieds_listings';
    protected $tableAlias = 'listing';
    protected $objectType = 'listing';

    /**
     * @var \modules\membership_plan\lib\ListingPackage\ListingPackageManager
     */
    private $_ListingPackageManager = null;
    private $_CategoryManager = null;
    private $_MembershipPlanManager = null;
    private $categoryTreeAdapter;
    private $listing_package_info = null;
    /**
     * @var \modules\users\lib\User\UserManager
     */
    private $_UserManager = null;

    public function setCategoryManager($m)
    {
        $this->_CategoryManager = $m;
    }

    public function setMembershipPlanManager($m)
    {
        $this->_MembershipPlanManager = $m;
    }

    public function setUserManager($m)
    {
        $this->_UserManager = $m;
    }

    public function setListingPackageManager($m)
    {
        $this->_ListingPackageManager = $m;
    }

    public function setCategoryTreeAdapter($a)
    {
        $this->categoryTreeAdapter = $a;
    }

    public static $system_details = array
    (
        array
        (
            'id' => 'sid',
            'caption' => 'Id',
            'type' => 'integer',
            'length' => '6',
            'is_required' => false,
            'is_system' => true,
            'order' => null,
        ),
        array
        (
            'id' => 'activation_date',
            'caption' => 'Activation Date',
            'type' => 'date',
            'length' => '20',
            'is_system' => true,
            'order' => null,
        ),
        array
        (
            'id' => 'user_sid',
            'caption' => 'User sid',
            'type' => 'integer',
            'is_required' => false,
            'is_system' => true,
            'order' => null,
        ),
        array
        (
            'id' => 'meta_keywords',
            'caption' => 'Meta Keywords',
            'type' => 'text',
            'is_required' => false,
            'is_system' => false,
            'input_template' => 'textarea.tpl'
        ),
        array
        (
            'id' => 'meta_description',
            'caption' => 'Meta Description',
            'type' => 'text',
            'is_required' => false,
            'is_system' => false,
            'input_template' => 'textarea.tpl'
        ),
        array
        (
            'id' => 'page_title',
            'caption' => 'Page Title',
            'type' => 'text',
            'is_required' => false,
            'is_system' => false,
            'input_template' => 'textarea.tpl'
        ),
    );

    function &order_minus(&$extra_details, $diff)
    {
        foreach ($extra_details as $key => $value) $extra_details[$key]['order'] += $diff;
        return $extra_details;
    }

    function addUsernameProperty($username = null)
    {
        $this->addProperty
        (
            array
            (
                'id' => 'username',
                'type' => 'string',
                'value' => $username,
                'is_system' => true,
                'table_name' => 'users_users',
                'column_name' => 'username',
                'join_condition' => array('key_column' => 'user_sid', 'foriegn_column' => 'sid'),
                'autocomplete_service_name' => 'UserManager',
                'autocomplete_method_name' => 'Username'
            )
        );
    }

    function addCategoryIDProperty($type_id)
    {

        $tree_values = $this->categoryTreeAdapter->getTreeStructure();

        $this->addProperty
        (
            array
            (
                'id' => 'category_sid',
                'type' => 'tree',
                'levels_ids' => 'Category',
                'value' => $type_id,
                'is_system' => true,
                'tree_values' => $tree_values,
                'tree_depth' => $this->categoryTreeAdapter->getDepth(),
            )
        );
    }

    function addActivationDateProperty($activation_date = null)
    {
        $this->addProperty
        (
            array
            (
                'id' => 'activation_date',
                'caption' => 'Activation Date',
                'type' => 'date',
                'value' => $activation_date,
                'is_system' => true,
            )
        );
    }

    function addExpirationDateProperty($expiration_date = null)
    {
        $this->addProperty
        (
            array
            (
                'id' => 'expiration_date',
                'type' => 'date',
                'value' => $expiration_date,
                'is_system' => true,
            )
        );
    }

    function addActiveProperty($is_active)
    {
        $this->addProperty
        (
            array
            (
                'id' => 'active',
                'caption' => 'Active',
                'type' => 'boolean',
                'value' => $is_active,
                'is_system' => true,
            )
        );
    }

    function addKeywordsProperty($keywords)
    {
        $this->addProperty
        (
            array
            (
                'id' => 'keywords',
                'type' => 'text',
                'value' => $keywords,
                'is_system' => true,
                'caption' => 'Keywords',
                'autocomplete_service_name' => 'ListingManager',
                'autocomplete_method_name' => 'ListingKeywords'
            )
        );
    }

    function addPicturesProperty()
    {
        $attachements = isset($this->data['images'])
            ? $this->data['images']
            : \App()->DB->query("SELECT * FROM `classifieds_listings_pictures` WHERE `listing_sid` = ?n ORDER BY `order`", $this->object_sid);

        $this->addProperty
        (
            array
            (
                'id' => 'pictures',
                'type' => 'pictures',
                'is_system' => true,
                'caption' => 'Pictures',
                'value' => '',
                'attachments' => $attachements,
                'table' => 'classifieds_listings_pictures',
                'key' => 'listing_sid',
                'styles' => [
                    'thumbnail' => [
                        'dimensions' => \App()->SettingsFromDB->getSettingByName('listing_thumbnail_width') . 'x' . \App()->SettingsFromDB->getSettingByName('listing_thumbnail_height') . '#'
                    ],
                    'picture' => [
                        'dimensions' => \App()->SettingsFromDB->getSettingByName('listing_picture_width') . 'x' . \App()->SettingsFromDB->getSettingByName('listing_picture_height') . '#',
                        'convert_options' => ['watermark' => true, 'CropBalanced' => false],
                    ],
                    'large' => [
                        'dimensions' => \App()->SettingsFromDB->getSettingByName('listing_big_picture_width') . 'x' . \App()->SettingsFromDB->getSettingByName('listing_big_picture_height'),
                        'convert_options' => ['watermark' => true],
                    ],
                ],
                'limit' => $this->getPicLimit(),
            )
        );
    }

    public function getPicLimit()
    {
        $info = isset($this->listing_package_info)
            ? $this->listing_package_info
            : $this->_ListingPackageManager->getPackageInfoByListingSID($this->object_sid);
        return $info['pic_limit'];
    }

    function addIDProperty($id)
    {
        $this->addProperty
        (
            array
            (
                'id' => 'id',
                'type' => 'string',
                'is_system' => true,
                'caption' => 'ID',
                'value' => $id,
                'column_name' => 'sid',
                'save_into_db' => false,
            )
        );
    }

    function addNumberOfViewsProperty($number_of_views)
    {

        $this->addProperty
        (
            array
            (
                'id' => 'views',
                'type' => 'string',
                'is_system' => true,
                'caption' => 'Views',
                'value' => $number_of_views,
            )
        );
    }

    function addUserProperty()
    {
        $user = isset($this->data['user'])
            ? $this->_UserManager->createUser($this->data['user'], $this->data['user_group_sid']) :
            $this->_UserManager->getObjectBySID($this->user_sid);
        $this->addProperty(array(
            'id' => 'user',
            'type' => 'object',
            'save_into_db' => false,
            'is_system' => true,
            'caption' => 'Listing User',
            'value' => empty($user) ? null : $user
        ));
    }

    function addPackageProperty($listing_sid)
    {
        $this->addProperty(array(
            'id' => 'package',
            'type' => 'relation',
            'is_system' => true,
            'save_into_db' => false,
            'caption' => 'Listing Package',
            'value' => $this->_ListingPackageManager->createTemplateStructureForPackageByListingSID($listing_sid)
        ));
    }

    function addCategoryProperty($category_sid)
    {
        $category_info = $this->_CategoryManager->getInfoBySID($category_sid);
        $this->addProperty(array(
            'id' => 'type',
            'type' => 'relation',
            'is_system' => true,
            'caption' => 'Category',
            'save_into_db' => false,
            'value' => array(
                'sid' => $category_info['sid'],
                'id' => $category_info['id'],
                'caption' => $category_info['name']
            )
        ));
    }

    function addListingPackageIDProperty($package_id)
    {
        $plans = $this->_MembershipPlanManager->getAllMembershipPlansInfoWithPackagesInfo();
        $list_values = array();

        foreach ($plans as $planInfo) {
            $packages = $planInfo['packages'];
            foreach ($packages as $packageInfo) {
                $list_values[] = array('sid' => $packageInfo['sid'], 'caption' => $packageInfo['name'], 'parent_name' => $planInfo['name']);
            }
        }

        $propertyInfo = array
        (
            'id' => 'listing_package',
            'caption' => 'Listing Package',
            'type' => 'list',
            'is_required' => false,
            'is_system' => true,
            'table_name' => 'membership_plan_listing_packages',
            'column_name' => 'package_sid',
            'join_condition' => array('key_column' => 'sid', 'foriegn_column' => 'listing_sid'),
            'list_values' => $list_values,
            'save_into_db' => false,
            'value' => $package_id,
        );

        $this->addProperty($propertyInfo);

        return array
        (
            'id' => 'listing_package',
            'real_id' => 'sid',
            'transform_function' => 'ListingPackageManager::getListingSIDsByPackageSID',
        );
    }

    function addLastUserIpProperty($ipValue)
    {

        $this->addProperty
        (
            array
            (
                'id' => 'last_user_ip',
                'type' => 'string',
                'is_system' => true,
                'caption' => '',
                'value' => $ipValue
            )
        );
    }

    public function lazyLoadListingPackageProperty($package_id = null)
    {
        return $this->addListingPackageIDProperty($package_id);
    }

    public function lazyLoadUserProperty()
    {
        $this->addUserProperty();
    }

    public function lazyLoadUsernameProperty()
    {
        $this->addUsernameProperty();
    }

    public function lazyLoadPackageProperty()
    {
        $this->addPackageProperty($this->data['sid']);
    }

    public function lazyLoadPicturesProperty()
    {
        $this->addPicturesProperty();
    }

    public function __clone()
    {
        foreach (array_keys($this->properties) as $id) $this->properties[$id] = clone $this->properties[$id];
    }


    /**
     * @param null $listing_package_info
     */
    public function setListingPackageInfo($listing_package_info)
    {
        $this->listing_package_info = $listing_package_info;
    }

}

?>

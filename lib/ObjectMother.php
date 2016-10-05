<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib;

use core\FileSystem;
use core\WrappedFunctions;
use DB;
use lib\Actions\ActionStorage;
use lib\Actions\ChoiceAction;
use lib\Actions\DisplayTemplateAction;
use lib\Actions\RedirectAction;
use lib\Actions\SequenceAction;
use lib\Actions\StubAction;
use lib\Criteria\LessEqualThenCriterion;
use lib\Criteria\TrueCriterion;
use lib\DataTransceiver\DataTransceiverFactory;
use lib\Forms\Form;
use lib\Forms\FormCollection;
use lib\NullAction;
use lib\ORM\Object;
use lib\ORM\ObjectDetails;
use lib\ORM\ObjectFilesManager;
use lib\ORM\Rating\RatingManager;
use lib\Reflection\ReflectionFactory;
use lib\Reflection\RequestReflector;
use lib\Validation\GeneralValidationFactory;
use lib\Validation\ListingMeetPackageConditionsValidator;
use modules\banners\lib\Banner\BannersReplacer;
use modules\classifieds\lib\Actions\CategoryCounterAction;
use modules\classifieds\lib\Browse\BrowseManager;
use modules\classifieds\lib\Browse\CategorySearcherFactory;
use modules\classifieds\lib\Calendar\Calendar;
use modules\classifieds\lib\Calendar\CalendarActionFactory;
use modules\classifieds\lib\Calendar\CalendarDatasource;
use modules\classifieds\lib\Calendar\CalendarGenerator;
use modules\classifieds\lib\Calendar\CalendarManager;
use modules\classifieds\lib\Calendar\CalendarValidatorFactory;
use modules\classifieds\lib\Category\CategoriesReplacer;
use modules\classifieds\lib\ExpiredListingsUserReporter;
use modules\classifieds\lib\ExpiredUserListingsLogger;
use modules\classifieds\lib\ExpireListingsProcessor;
use modules\classifieds\lib\ExpireUserListingsAction;
use modules\classifieds\lib\Listing\ActivateListingAction;
use modules\classifieds\lib\Listing\ActivateListingFeatureAction;
use modules\classifieds\lib\Listing\AddListingToComparisonAction;
use modules\classifieds\lib\Listing\ClearComparisonAction;
use modules\classifieds\lib\Listing\DeactivateListingsAction;
use modules\classifieds\lib\Listing\ListingComparisonTable;
use modules\classifieds\lib\Listing\ListingCountSummator;
use modules\classifieds\lib\Listing\ListingDisplayer;
use modules\classifieds\lib\Listing\ListingEraser;
use modules\classifieds\lib\Listing\RemoveListingFromComparisonAction;
use modules\classifieds\lib\Listing\RestoreListingsOnSubscriptionAction;
use modules\classifieds\lib\ListingField\ListingFieldCollector;
use modules\classifieds\lib\ListingField\ListingFieldListItemsReplacer;
use modules\classifieds\lib\ListingField\ListingFieldsReplacer;
use modules\classifieds\lib\ListingField\ListingFieldTreeItemsReplacer;
use modules\classifieds\lib\ListingPriceCalculator;
use modules\classifieds\lib\SavedListingsForUserLoggedIn;
use modules\classifieds\lib\SavedListingsForUserNotLoggedIn;
use modules\classifieds\lib\SendContactSellerFormMessageAction;
use modules\classifieds\lib\SendTellFriendFormMessageAction;
use modules\I18N\lib\Formatters\DateFormatter;
use modules\I18N\lib\Formatters\FloatFormatter;
use modules\image_carousel\lib\CarouselImage\CarouselImagesReplacer;
use modules\main\lib\AdminManager;
use modules\membership_plan\lib\ApplyPackageChangesToContractsAction;
use modules\membership_plan\lib\ApplyPackageChangesToListingsAction;
use modules\membership_plan\lib\ApplyPackageChangesToSubDomainAction;
use modules\membership_plan\lib\AssignUserContractAction;
use modules\membership_plan\lib\AutoExtendUserContractAction;
use modules\membership_plan\lib\AutoExtendUserContractActionHelper;
use modules\membership_plan\lib\DisableAutoExtendForContractsAction;
use modules\membership_plan\lib\ExpireUserContractAction;
use modules\membership_plan\lib\InformUsersAutoExtendCanceled;
use modules\miscellaneous\lib\AdminReporter;
use modules\miscellaneous\lib\ArrayCombiner;
use modules\miscellaneous\lib\Captcha;
use modules\miscellaneous\lib\FormFieldsFilter;
use modules\miscellaneous\lib\HTMLPurifierConverter;
use modules\miscellaneous\lib\HTMLTagConverter;
use modules\miscellaneous\lib\NullConverter;
use modules\miscellaneous\lib\Paging;
use modules\miscellaneous\lib\PagingDatasource;
use modules\miscellaneous\lib\RssReader;
use modules\miscellaneous\lib\RssReaderWithCache;
use modules\miscellaneous\lib\SendContactUsFormMessageAction;
use modules\miscellaneous\lib\StructureExplorer;
use modules\miscellaneous\lib\TreeBuilder;
use modules\miscellaneous\lib\TreeData;
use modules\miscellaneous\lib\TreeItem;
use modules\miscellaneous\lib\TreeWalker;
use modules\miscellaneous\lib\TreeWalkerHandlerIdCollector;
use modules\miscellaneous\lib\VersionReader;
use modules\smarty_based_template_processor\lib\Return404Action;
use modules\smarty_based_template_processor\lib\ReturnNullAction;
use modules\smarty_based_template_processor\lib\ThrowFileNotFoundExceptionAction;
use modules\users\lib\RedirectAfterLoginAction;
use modules\users\lib\SendUserContactFormMessageAction;
use modules\users\lib\User\UserEraser;
use modules\users\lib\UserProfileField\UserProfileFieldListItemsReplacer;
use modules\users\lib\UserProfileField\UserProfileFieldsReplacer;
use modules\users\lib\UserProfileField\UserProfileFieldTreeItemsReplacer;

class ObjectMother implements \core\IService
{
	public function init()
	{
		
	}

	public function createUser($user_info = array(), $user_group_sid = 0) {
				$user = \App()->UserManager->createUser($user_info, $user_group_sid);
		return $user;
	}

	public function createForm($object = null, $fieldsOrder = array(), $captchaEnabled = false) {
		$registration_form = new Form($object, $fieldsOrder, $captchaEnabled);
		return $registration_form;
	}

	public function createListing($listing_info = array(), $category_sid = 0) {
				$listing = $this->getListingFactory()->getListing($listing_info, $category_sid);
		return $listing;
	}

	public function createCategorySearcherFactory()
	{
		$instance = new CategorySearcherFactory();
		return $instance;
	}
	public function createBrowseManager($category_id, $userSid = null)
	{
		$instance = new BrowseManager($category_id, $userSid);
        $instance->setNumberOfLevels((!empty($_REQUEST['number_of_levels']) && is_numeric($_REQUEST['number_of_levels'])) ? $_REQUEST['number_of_levels'] : null);
		$instance->setRootCategoryId($category_id);
		$instance->init();

        if (isset($_REQUEST['view_all']))
        {
            $instance->setCurrnetLevelAsLastLevel();
        }

		return $instance;
	}

	public function createListingFieldListItemManager()
	{
		$instance = new \modules\classifieds\lib\ListingField\ListingFieldListItemManager();
		return $instance;
	}

	public function createCategoryActionFactory()
	{
		$instance = new \modules\classifieds\lib\Category\CategoryActionFactory();

		$categoryManager = $this->createCategoryManager();
		$listingManager = $this->createListingManager();
		$treeWalker = $this->createTreeWalker();
		$listingCountSummator = $this->createListingCountSummator();
		$listingFieldManager = $this->createListingFieldManager();
		$listingFieldCollector = $this->createListingFieldCollector();

		$instance->setCategoryManager($categoryManager);
		$instance->setListingManager($listingManager);
		$instance->setTreeWalker($treeWalker);
		$instance->setListingCountSummator($listingCountSummator);
		$instance->setListingFieldManager($listingFieldManager);
		$instance->setListingFieldCollector($listingFieldCollector);

		return $instance;
	}

	public function createCategoryManager()
	{
		return \App()->CategoryManager;
	}

	public function createListingManager()
	{
		return \App()->ListingManager;
	}

	public function createAdmin()
	{
		return new AdminManager();
	}

	public function createTreeWalker()
	{
				$instance = new TreeWalker();
		return $instance;
	}

	public function createTreeWalkerHandlerIdCollector()
	{
				$instance = new TreeWalkerHandlerIdCollector();
		return $instance;
	}

	public function createListingCountSummator()
	{
				$instance = new ListingCountSummator();
		return $instance;
	}

	public function createListingFieldCollector()
	{
				$instance = new ListingFieldCollector();
		return $instance;
	}

	public function createNullAction()
	{
		if (!isset($GLOBALS['ObjectMother_instances_NullAction']))
		{
						$GLOBALS['ObjectMother_instances_NullAction'] = new NullAction();
		}
		return $GLOBALS['ObjectMother_instances_NullAction'];
	}

	public function createTreeBuilder()
	{
				$instance = new TreeBuilder();
		return $instance;
	}

	public function createTreeData()
	{
		$instance = new TreeData();
		return $instance;
	}

	public function createTreeItem($id, $parent_id)
	{
		$instance = new TreeItem();
		$instance->setID($id);
		$instance->setParentID($parent_id);
		return $instance;
	}

	public function createHTMLTagConverterInArray()
	{
		if (empty($GLOBALS['ObjectMother_instances_HtmlTagConverterInArray']))
		{
			$explorer = new StructureExplorer();
			$htmlTagConverter = $this->createHTMLTagConverter();
			$explorer->addFilter('gettype($value) === "string"');
			$explorer->addFilter('strrpos($value, ">" ) !== false || strrpos($value, "\"" ) !== false');
			$explorer->addFilter('strpos($value, "JFIF" ) === false');
			$explorer->setEventHandler(array($htmlTagConverter, 'getConverted'));
			$GLOBALS['ObjectMother_instances_HtmlTagConverterInArray'] = $explorer;
		}
		return $GLOBALS['ObjectMother_instances_HtmlTagConverterInArray'];
	}

	public function createHTMLTagConverter()
	{
		if (empty($GLOBALS['ObjectMother_instances_HtmlTagConverter']))
		{
            switch(\App()->SettingsFromDB->getSettingByName('escape_html_tags')) {
                case 'htmlentities' :
                    $GLOBALS['ObjectMother_instances_HtmlTagConverter'] = new HTMLTagConverter();
                    break;
                case 'htmlpurifier' :
                    $GLOBALS['ObjectMother_instances_HtmlTagConverter'] = new HTMLPurifierConverter(\App()->FileSystem->getWritableCacheDir('HTMLPurifierConverter'));
                    break;
                default:
                    $GLOBALS['ObjectMother_instances_HtmlTagConverter'] = new NullConverter();
            }
		}
		return $GLOBALS['ObjectMother_instances_HtmlTagConverter'];
	}

	public function createSavedListings()
	{
		if (\App()->UserManager->isUserLoggedIn())
		{
			$savedListings = $this->createSavedListingsForUserLoggedIn(\App()->UserManager->getCurrentUserSID());
		}
		else
		{
			$savedListings = new SavedListingsForUserNotLoggedIn();
		}
		return $savedListings;
	}

	public function createSavedListingsForUserLoggedIn($userSid)
	{
				$instance = new SavedListingsForUserLoggedIn();
		$instance->setUserSid($userSid);
		$instance->setDB(\App()->DB);
		return $instance;
	}

	public function createFileSystem()
	{
		$instance = new FileSystem();
		return $instance;
	}

	public function createDB()
	{
		$instance = new DB();
		return $instance;
	}

	public function createReflectionFactory()
	{
		$instance = new ReflectionFactory();
		return $instance;
	}

	public function createUserListPagingDatasource()
	{
		$default = array('items_per_page' => 10, 'page' => '1');
		$request = $_REQUEST;
		$session = $_SESSION['UserListPagingDatasource'];
		if (!isset($session) || empty($request['restore']))
		{
			$session = array();
		}

		$reflectionFactory = $this->createReflectionFactory();

		$instance = new PagingDatasource();
		$instance->setRequestData($reflectionFactory->createHashtableReflector($request));
		$instance->setSessionData($reflectionFactory->createHashtableReflector($session));
		$instance->setDefaultData($reflectionFactory->createHashtableReflector($default));
		return $instance;
	}

	public function createUserListPaging(&$items)
	{
		$instance = new Paging();
		$instance->setDatasource($this->createUserListPagingDatasource());
		$instance->setItems($items);
		return $instance;
	}

	public function createCaptcha()
	{
		return new Captcha();
	}

	public function createAddListingToComparisonAction($requestData)
	{
		$reflectionFactory = $this->createReflectionFactory();
		$data = $reflectionFactory->createHashtableReflector($requestData);

		$instance = new AddListingToComparisonAction();
		$instance->setDataSource($data);
		$instance->setListingComparisonTable($this->createListingComparisonTable());
		return $instance;
	}

	public function createRemoveListingFromComparisonAction($requestData)
	{
		$reflectionFactory = $this->createReflectionFactory();
		$data = $reflectionFactory->createHashtableReflector($requestData);

		$instance = new RemoveListingFromComparisonAction();
		$instance->setDataSource($data);
		$instance->setListingComparisonTable($this->createListingComparisonTable());
		return $instance;
	}

	public function createClearComparisonAction()
	{
		$instance = new ClearComparisonAction();
		$instance->setListingComparisonTable($this->createListingComparisonTable());
		return $instance;
	}

	public function createListingComparisonTable()
	{
		$instance = new ListingComparisonTable();
		$instance->setDatasource(\App()->Session->getContainer('ListingComparisonTable'));
		return $instance;
	}

	public function createFormCollection($objects)
	{
		$instance = new FormCollection($objects);
		return $instance;
	}

	public function createDataTransceiverFactory()
	{
		$instance = new DataTransceiverFactory();
		return $instance;
	}

	public function createArrayCombiner()
	{
		$instance = new ArrayCombiner();
		return $instance;
	}

	public function createUserGroupManager()
	{
		return \App()->UserGroupManager;
	}

	public function createFormFieldsFilter($fieldsToDisplay)
	{
		$instance = new FormFieldsFilter();
		$instance->setFormFields($fieldsToDisplay);
		return $instance;
	}

	public function createContactUsFormObject($request)
	{
		$instance = new Object();
		$instance->details = new ObjectDetails($request);
		$instance->details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$properties = array
		(
		//	array('id', 'caption', 'type', 'is_required'),
			array('name', 'Salutation, First and Last Name', 'string', true),
			array('email', 'Email', 'email', true),
			array('comments', 'Comments', 'text', true),
		);
		foreach ($properties as $property)
		{
			$instance->addProperty(array
			(
				'id' => $property[0],
				'caption' => $property[1],
				'type' => $property[2],
				'is_required' => $property[3],
				'value' => isset($request[$property[0]])? $request[$property[0]]: null,
			));
		}
		return $instance;
	}
	public function createContactFormObject($request)
	{
		$instance = new Object();
		$instance->details = new ObjectDetails($request);
		$instance->details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$properties = array
		(
		//	array('id', 'caption', 'type', 'is_required'),
			array('FullName', 'Your Name', 'string', true),
			array('Email', 'Your e-mail', 'string', true),
			array('Request', 'Your request', 'text', true),
		);
		foreach ($properties as $property)
		{
			$instance->addProperty(array
			(
				'id' => $property[0],
				'caption' => $property[1],
				'type' => $property[2],
				'is_required' => $property[3],
				'value' => isset($request[$property[0]])? $request[$property[0]]: null,
			));
		}
		return $instance;
	}
	public function createReportImproperContentFormObject($request)
	{
		$instance = new Object();
		$instance->details = new ObjectDetails($request);
		$instance->details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$properties = array
		(
		//	array('id', 'caption', 'type', 'is_required'),
			array('FullName', 'Your Name', 'string', true),
			array('Email', 'Your e-mail', 'email', true),
			array('Report', 'Your report', 'text', true),
		);
		foreach ($properties as $property)
		{
			$instance->addProperty(array
			(
				'id' => $property[0],
				'caption' => $property[1],
				'type' => $property[2],
				'is_required' => $property[3],
				'value' => isset($request[$property[0]])? $request[$property[0]]: null,
			));
		}
		return $instance;
	}

	public function createSendUserContactFormMessageAction($request, $userSid)
	{

		$template_processor = \App()->getTemplateProcessor();
		$contactFormObject = $this->createContactFormObject($request);
		$form = $this->createForm($contactFormObject, array(), \App()->SettingsFromDB->getSettingByName('captcha_in_contact_user_form'));
		$form->registerTags($template_processor);
		$formSubmitted = (isset($request['action']) && $request['action'] == 'send_message');

		$instance = new SendUserContactFormMessageAction();
		$instance->setUserSid($userSid);
		$instance->setForm($form);
		$instance->setTemplateProcessor($template_processor);
		$instance->setContactFormObject($contactFormObject);
		$instance->setFormSubmitted($formSubmitted);
        $instance->setRequestData($request);
		if (isset($request['display_template']))
		{
			$instance->setDisplayTemplate($request['display_template']);
		}
	 	return $instance;
	}

	public function createReportImproperContentAction($request)
	{
		$template_processor = \App()->getTemplateProcessor();
		$formObject = $this->createReportImproperContentFormObject($request);
		$form = $this->createForm($formObject, [], \App()->SettingsFromDB->getSettingByName('captcha_in_report_improper_content_form'));
		$form->registerTags($template_processor);
		$formSubmitted = (isset($request['action']) && $request['action'] == 'report');

		$instance = new \modules\miscellaneous\lib\ReportImproperContentAction();
		$instance->setForm($form);
		$instance->setTemplateProcessor($template_processor);
		$instance->setFormObject($formObject);
		$instance->setFormSubmitted($formSubmitted);
		$instance->setObjectType($request['objectType']);
		$instance->setObjectId($request['objectId']);
        if (isset($request['returnBackUri']))
        {
	        $instance->setReturnBackUri($request['returnBackUri']);
        }
	 	return $instance;
	}

	public function createRequestReflector()
	{
		$instance = new RequestReflector();
		return $instance;
	}

	public function createActivateListingAction($listingSid)
	{
		$listingManager = $this->createListingManager();
		$instance = new ActivateListingAction();
		$instance->setListingSid($listingSid);
		$instance->setListingManager($listingManager);
		return $instance;
	}

	public function createActivateListingFeatureAction($listingSid, $featureId)
	{
		$listing = \App()->ListingManager->getObjectBySID($listingSid);

		$instance = new ActivateListingFeatureAction();
		$instance->setListing($listing);
		$instance->setFeatureId($featureId);
		return $instance;
	}

	public function createContactSellerFormObject($request)
	{
		$instance = new Object();
		$instance->details = new ObjectDetails($request);
		$instance->details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$properties = array
		(
		//	array('id', 'caption', 'type', 'is_required'),
			array('FullName', 'Your name', 'string', true),
			array('Email', 'Your e-mail', 'email', true),
			array('Request', 'Your request', 'text', true),
		);
		foreach ($properties as $property)
		{
			$instance->addProperty(array
			(
				'id' => $property[0],
				'caption' => $property[1],
				'type' => $property[2],
				'is_required' => $property[3],
				'value' => isset($request[$property[0]])? $request[$property[0]]: null,
			));
		}
		return $instance;
	}

	public function createSendContactSellerFormMessageAction($request, $userSid, $listingSid)
	{
		$template_processor = \App()->getTemplateProcessor();
		$contactFormObject = $this->createContactSellerFormObject($request);
		$form = $this->createForm($contactFormObject, array(), \App()->SettingsFromDB->getSettingByName('captcha_in_contact_seller_form'));
		$form->registerTags($template_processor);
		$formSubmitted = \App()->Request['action'] == 'send_message';

		$instance = new SendContactSellerFormMessageAction();
		$instance->setUserSid($userSid);
		$instance->setListingSid($listingSid);
		$instance->setForm($form);
		$instance->setTemplateProcessor($template_processor);
		$instance->setContactFormObject($contactFormObject);
		$instance->setFormSubmitted($formSubmitted);
		if (isset($request['display_template']))
		{
			$instance->setDisplayTemplate($request['display_template']);
		}
	 	return $instance;
	}

	public function createTellFriendFormObject($request)
	{
		$instance = new Object();
		$instance->details = new ObjectDetails($request);
		$instance->details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$properties = array
		(
		//	array('id', 'caption', 'type', 'is_required'),
			array('name', 'Your name', 'string', true),
			array('friend_name', "Your friend's name", 'string', true),
			array('friend_email', "Your friend's e-mail address", 'email', true),
			array('comment', 'Your comment (will be sent with the recommendation)', 'text', true),
		);
		foreach ($properties as $property)
		{
			$instance->addProperty(array
			(
				'id' => $property[0],
				'caption' => $property[1],
				'type' => $property[2],
				'is_required' => $property[3],
				'value' => isset($request[$property[0]])? $request[$property[0]]: null,
			));
		}
		return $instance;
	}

	public function createSendTellFriendFormMessageAction($request, $listingSid)
	{
		$template_processor = \App()->getTemplateProcessor();
		$contactFormObject = $this->createTellFriendFormObject($request);
		$form = $this->createForm($contactFormObject, array(), \App()->SettingsFromDB->getSettingByName('captcha_in_tell_friend_form'));
		$form->registerTags($template_processor);
		$formSubmitted = (isset($request['action']) && $request['action'] == 'send_message');

		$instance = new SendTellFriendFormMessageAction();
		$instance->setListingSid($listingSid);
		$instance->setForm($form);
		$instance->setTemplateProcessor($template_processor);
		$instance->setContactFormObject($contactFormObject);
		$instance->setFormSubmitted($formSubmitted);
		if (isset($request['display_template']))
		{
			$instance->setDisplayTemplate($request['display_template']);
		}
	 	return $instance;
	}

	public function createSendContactUsMessageAction($request, $ignoreCaptcha = false)
	{
		$template_processor = \App()->getTemplateProcessor();
		$contactUsFormObject = $this->createContactUsFormObject($request);
		$captchaEnabled = $ignoreCaptcha ? false : \App()->SettingsFromDB->getSettingByName('captcha_in_contact_form');
		$form = $this->createForm($contactUsFormObject, array(), $captchaEnabled);
		$form->registerTags($template_processor);
		$formSubmitted = (isset($request['action']) && $request['action'] == 'send_message');
		$form->setFormSubmitted($formSubmitted);

		$instance = new SendContactUsFormMessageAction();
		$instance->setForm($form);
		$instance->setTemplateProcessor($template_processor);
		$instance->setContactFormObject($contactUsFormObject);
		$instance->setFormSubmitted($formSubmitted);
	 	return $instance;
	}

	public function createGeneralValidationFactory()
	{
		$instance = new GeneralValidationFactory();
		return $instance;
	}

	public function createSequenceAction()
	{
		$instance = new SequenceAction();
		return $instance;
	}

	public function createRedirectAction($url)
	{
		$instance = new RedirectAction();
		$instance->setURL($url);
		return $instance;
	}

	public function createChoiceAction($abilityCriterion, $onSuccess, $onFailure)
	{
		$instance = new ChoiceAction();
		$instance->setAbilityCriterion($abilityCriterion);
		$instance->setOnSuccess($onSuccess);
		$instance->setOnFailure($onFailure);
		return $instance;
	}

	public function createDisplayTemplateAction($templateName, $templateVariables = array())
	{
		$instance = new DisplayTemplateAction();
		$instance->setTemplateName($templateName);
		$instance->setTemplateVariables($templateVariables);
		return $instance;
	}

	public function createActionStorage()
	{
		$instance = new ActionStorage();
		$instance->init();
		return $instance;
	}

	public function createStubAction($message)
	{
		$instance = new StubAction($message);
		return $instance;
	}

	public function createAssignUserContractAction($membershipPlanId, &$user, $autoExtend = false)
	{
		$assignUserContractAction = new AssignUserContractAction();
		$assignUserContractAction->setMembershipPlanId($membershipPlanId);
		$assignUserContractAction->setUser($user);
		$assignUserContractAction->setUserManager(\App()->UserManager);
		$assignUserContractAction->setAutoExtend($autoExtend);
		$assignUserContractAction->setContractManager(\App()->ContractManager);
		
		$action = $this->createSequenceAction();
		$action->push($assignUserContractAction);
		$action->push($this->createRestoreListingsOnSubscriptionAction($user));
		return $action;
	}

	public function createRestoreListingsOnSubscriptionAction(&$user)
	{
		$instance = new RestoreListingsOnSubscriptionAction();
		$instance->setUser($user);
		return $instance;
	}
	
	public function createLessEqualThenCriterion($value1, $value2)
	{
		$instance = new LessEqualThenCriterion();
		$instance->setValue1($value1);
		$instance->setValue2($value2);
		return $instance;
	}
	
	public function createTrueCriterion($value)
	{
		$instance = new TrueCriterion();
		$instance->setValue($value);
		return $instance;
	}
	
	public function createExpireUserContractAction($contract)
	{
		$expireUserContractAction = new ExpireUserContractAction();
		$expireUserContractAction->setContract($contract);
		$expireUserContractAction->setContractManager(\App()->ContractManager);
		$action = $this->createSequenceAction();
		$action->push($expireUserContractAction);
		if ($contract->getType() == 'Subscription')
		{
			$listingManager = $this->createListingManager();
			$userListings = $listingManager->getListingsSIDByUserSID(\App()->UserManager->getUserSIDByContractID($contract->getID()));
			
			$deactivateListingsAction = new DeactivateListingsAction();
			$deactivateListingsAction->setListingManager($listingManager);
			$deactivateListingsAction->setListingsId($userListings);
			$action->push($deactivateListingsAction);
		}
		return $action;
	}

	public function createAutoExtendUserContractAction($contractId)
	{
		$contract = \App()->ContractManager->getContractBySID($contractId);
		$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanBySID($contract->getMembershipPlanSID());
		$user = \App()->UserManager->getObjectBySID(\App()->UserManager->getUserSIDByContractID($contractId));

		$helper = new AutoExtendUserContractActionHelper();
		$helper->setContract($contract);
		$helper->setMembershipPlan($membershipPlan);
		$helper->setUser($user);

		$instance = new AutoExtendUserContractAction();
		$instance->setHelper($helper);
		return $instance;
	}

	public function createMembershipPlanChangedPostAction($membershipPlan)
	{
		$contracts = \App()->ContractManager->getAutoExtendContractSIDsByMembershipPlanSID($membershipPlan->getSID());
		$usersToInform = array_map([\App()->UserManager, 'getUserSIDByContractID'], $contracts);
		
		$disableAutoExtendForContractsAction = $this->createDisableAutoExtendForContractsAction($contracts);
		$informUsersAutoExtendCanceled = $this->createInformUsersAutoExtendCanceled($usersToInform);
		
		$instance = $this->createSequenceAction();
		$instance->push($disableAutoExtendForContractsAction);
		$instance->push($informUsersAutoExtendCanceled);
		return $instance;
	}
	
	public function createDisableAutoExtendForContractsAction($contractsId)
	{
		$instance = new DisableAutoExtendForContractsAction();
		$instance->setContractsId($contractsId);
		$instance->setContractManager(\App()->ContractManager);
		return $instance;
	}
	
	public function createInformUsersAutoExtendCanceled($usersToInform)
	{
		$instance = new InformUsersAutoExtendCanceled();
		$instance->setUserSids($usersToInform);
		return $instance;
	}
	
	public function createListingFieldManager()
	{
		return \App()->ListingFieldManager;
	}
	
	public function createListingMeetPackageConditionsValidator(&$listing, &$userContract)
	{
		$instance = new ListingMeetPackageConditionsValidator();
		$instance->setListing($listing);
		$instance->setUserContract($userContract);
		$instance->setListingGallery(\App()->ListingGalleryManager->createListingGallery());
		$instance->setListingFieldManager($this->createListingFieldManager());
		return $instance;
	}
	
	public function createExpireListingsProcessor($listingsSid)
	{
		$instance = new ExpireListingsProcessor();
		$instance->setObjectMother($this);
		$instance->setListingsSid($listingsSid);
		$instance->setListingManager($this->createListingManager());
		$instance->setUserManager(\App()->UserManager);
		$instance->setAdminReporter($this->getAdminReporter());
		return $instance;
	}

	public function createExpireUserListingsAction($listingsSid)
	{
		$instance = new ExpireUserListingsAction();
		$instance->setListingsSid($listingsSid);
		return $instance;
	}

	public function createExpiredUserListingLogger($user)
	{
		$instance = new ExpiredUserListingsLogger();
		$instance->setUsername($user->getUserName());
		return $instance;
	}
	
	public function createListingPriceCalculator()
	{
		$instance = new ListingPriceCalculator();
		return $instance;
	}

	public function createExpiredListingsUserReporter(&$user, &$logger)
	{
		$instance = new ExpiredListingsUserReporter();
		$instance->setUser($user);
		$instance->setLogger($logger);
		return $instance;
	}

	public function createApplyPackageChangesToContractsAction($package)
	{
		$instance = new ApplyPackageChangesToContractsAction();
		$instance->setPackage($package);
		$instance->setContractManager(\App()->ContractManager);
		$instance->setContractPackagesManager(\App()->ContractPackagesManager);
		return $instance;
	}

	public function createApplyPackageChangesToListingsAction($package)
	{
		$instance = new ApplyPackageChangesToListingsAction();
		$instance->setPackage($package);
		$instance->setPackageManager(\App()->PackageManager);
		$instance->setListingPackageManager(\App()->ListingPackageManager);
		return $instance;
	}

	public function createApplyPackageChangesToSubDomainAction($package)
	{
		$instance = new ApplyPackageChangesToSubDomainAction();
		$instance->setPackage($package);
		$instance->setPackageManager(\App()->PackageManager);
		$instance->setSubDomainPackageManager(\App()->SubdomainPackageManager);
		return $instance;
	}

	/**
	 * @return AdminReporter
	 */
	public function getAdminReporter()
	{
		if (empty($GLOBALS['ObjectMother_instances_AdminReporter']))
		{
			$instance = new AdminReporter();
			$GLOBALS['ObjectMother_instances_AdminReporter'] = $instance;
		}
		return $GLOBALS['ObjectMother_instances_AdminReporter'];
	}

	public function createObjectFilesManager($objectManager)
	{
		$intance = new ObjectFilesManager();
		$intance->setObjectManager($objectManager);
		$intance->setUploadFileManager(\App()->UploadFileManager);
		return $intance;
	}

	public function createListingFilesManager()
	{
		$instance = $this->createObjectFilesManager($this->createListingManager());
		return $instance;
	}

	public function createUserFilesManager()
	{
		$instance = $this->createObjectFilesManager(\App()->UserManager);
		return $instance;
	}

	public function createListingEraser($listingSid)
	{
		$instance = new ListingEraser();
		$instance->setListingSid($listingSid)
            ->setListingGallery(\App()->ListingGalleryManager->createListingGallery())
		    ->setListingPackageManager(\App()->ListingPackageManager)
            ->setListingFilesManager($this->createListingFilesManager())
            ->setCalendarManager($this->createCalendarManager())
            ->setRatingManager($this->createRatingManager('listing'))
            ->setListingManager($this->createListingManager())
            ->setListing(\App()->ListingManager->getObjectBySID($listingSid));
		return $instance;
	}

	public function createUserEraser($userSid)
	{
		$instance = new UserEraser();
		$instance->setUserSid($userSid);
		$instance->setUserFilesManager($this->createUserFilesManager());
		$instance->setUserManager(\App()->UserManager);
		$instance->setContractManager(\App()->ContractManager);
		return $instance;
	}

	public function createPackageManager()
	{
		return \App()->PackageManager;
	}

	public function getListingFactory()
	{
		return \App()->ListingFactory;
	}
	
	function createCalendarManager()
	{
		$manager = new CalendarManager();
		$manager->setDB(\App()->DB);
		return $manager;
	}
	function createCalendarValidationFactory()
	{
		$rf = new ReflectionFactory();
		$validationFactory = new CalendarValidatorFactory();
		$validationFactory->setReflectionFactory($rf);
		return $validationFactory;
	}
	function createCalendarActionFactory()
	{
		$manager = $this->createCalendarManager();
		$validationFactory = $this->createCalendarValidationFactory();

		$factory = new CalendarActionFactory();
		$factory->setCalendarManager($manager);
		$factory->setValidationFactory($validationFactory);
		return $factory;
	}
	function createCalendar($listingSid, $fieldSid)
	{
		$manager = $this->createCalendarManager();

		$datasource = new CalendarDatasource();
		$datasource->setCalendarManager($manager);
		$datasource->setListingSid($listingSid);
		$datasource->setFieldSid($fieldSid);
		$datasource->setDefaultStatus('free');
		
		$gen = new CalendarGenerator();

		$calendar = new Calendar();
		$calendar->setGenerator($gen);
		$calendar->setDatasource($datasource);
		
		return $calendar;
	}
	function createDateFormatter($dateFormat)
	{
		$instance = new DateFormatter();
		$instance->setDateFormat($dateFormat);
		return $instance;
	}

	public function createListingFieldsReplacer($order, $parentValue)
	{
		$instance = new ListingFieldsReplacer();
		$instance->setNewOrder($order);
		$instance->setParentValue($parentValue);
		return $instance;
	}
	public function createCategoriesReplacer($order, $parentValue)
	{
		$instance = new CategoriesReplacer();
		$instance->setNewOrder($order);
		$instance->setParentValue($parentValue);
		return $instance;
	}
	public function createListingFieldListItemsReplacer($order, $parentValue)
	{
		$instance = new ListingFieldListItemsReplacer();
		$instance->setNewOrder($order);
		$instance->setParentValue($parentValue);
		return $instance;
	}
	public function createListingFieldTreeItemsReplacer($order, $parentValue, $parentNodeValue)
	{
		$instance = new ListingFieldTreeItemsReplacer();
		$instance->setNewOrder($order);
		$instance->setParentValue($parentValue);
		$instance->setParentNodeValue($parentNodeValue);
		return $instance;
	}
	public function createUserProfileFieldTreeItemsReplacer($order, $parentValue, $parentNodeValue)
	{
		$instance = new UserProfileFieldTreeItemsReplacer();
		$instance->setNewOrder($order);
		$instance->setParentValue($parentValue);
		$instance->setParentNodeValue($parentNodeValue);
		return $instance;
	}
	public function createUserProfileFieldsReplacer($order, $parentValue)
	{
		$instance = new UserProfileFieldsReplacer();
		$instance->setNewOrder($order);
		$instance->setParentValue($parentValue);
		return $instance;
	}
	public function createUserProfileFieldListItemsReplacer($order, $parentValue)
	{
		$instance = new UserProfileFieldListItemsReplacer();
		$instance->setNewOrder($order);
		$instance->setParentValue($parentValue);
		return $instance;
	}
	public function createBannersReplacer($order, $parentValue)
	{
		$instance = new BannersReplacer();
		$instance->setNewOrder($order);
		$instance->setParentValue($parentValue);
		return $instance;
	}
	public function createCarouselImagesReplacer($order)
	{
		$instance = new CarouselImagesReplacer();
		$instance->setNewOrder($order);
		return $instance;
	}

	public function createFloatFormatter($thousandsSeparator, $decimalPoint)
	{
		$instance = new FloatFormatter();
		$instance->setThousandsSeparator($thousandsSeparator);
		$instance->setDecimalPoint($decimalPoint);
		return $instance;
	}
	public function createRatingManager($objectProperty)
	{
		$instance = new RatingManager();
		$instance->setObjectProperty($objectProperty);
		return $instance;
	}

	public function createRedirectAfterLoginAction($httpReferer, $queryString, $currentPageUri)
	{
		$instance = new RedirectAfterLoginAction();
		$instance->setHttpReferer($httpReferer);
		$instance->setQueryString($queryString);
		$instance->setCurrentPageUri($currentPageUri);
		return $instance;
	}

	public function createListingDisplayer()
	{
		$instance = new ListingDisplayer();
		$instance->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
		return $instance;
	}

	public function createRssReaderWithCache()
	{
		$rssReader = new RssReader();
		$rssReaderWithCache = new RssReaderWithCache($rssReader);
		$rssReader->setWrappedFunctions(new WrappedFunctions());
		return $rssReaderWithCache;
	}

	public function createVersionReader()
	{
		return new VersionReader();
	}

	public function createFileNotFoundAction()
	{
		switch (\App()->SystemSettings['FileNotFoundAction'])
		{
			case 'ReturnNull':
				return new ReturnNullAction();
			case 'Return404':
				return new Return404Action(\App()->SystemSettings['SiteUrl'] . '/' . \App()->SystemSettings['Page404Uri']);
			case 'ThrowException':
				return new ThrowFileNotFoundExceptionAction();
		}
	}

	public function getDelimiterById($id)
	{
		$delimiters = array
		(
			"comma" => ",",
			"tab" => "\t",
			"colon" => ":",
			"semicolon" => ";",
			"pipe" => "|",
			"dot" => ".",
			'none' => "\0",
		);
		return isset($delimiters[$id]) ? $delimiters[$id] : null;
	}

	public function getDelimitersListValues()
	{
		return array
		(
			array(
				'id' => 'comma',
				'caption' => 'Comma (,)',
			),
			array(
				'id' => 'tab',
				'caption' => 'Tabulator',
			),
			array(
				'id' => 'colon',
				'caption' => 'Colon (:)',
			),
			array(
				'id' => 'semicolon',
				'caption' => 'Semicolon (;)',
			),
			array(
				'id' => 'pipe',
				'caption' => 'Pipe (|)',
			),
			array(
				'id' => 'dot',
				'caption' => 'Dot (.)',
			),
		);
	}

	public function getReflectionObject($object)
	{
		return new \ReflectionObject($object);
	}

    /**
     * @param int $categorySid
     * @return CategoryCounterAction
     */
    public function getCategoryCountAllListingAction($categorySid)
    {
        $instance = new CategoryCounterAction();
        $instance->setCategorySid((int)$categorySid);
        $instance->setFieldName('listing_number');
        return $instance;
    }

    /**
     * @param int $categorySid
     * @return CategoryCounterAction
     */
    public function getCategoryCountActiveListingAction($categorySid)
    {
        $instance = new CategoryCounterAction();
        $instance->setCategorySid((int)$categorySid);
        $instance->setFieldName('active_listing_number');
        return $instance;
    }
}

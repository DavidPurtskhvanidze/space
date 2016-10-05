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


namespace modules\users\apps\AdminPanel\scripts;

// version 5 wrapper header

class UsersHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\users\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'users';
	protected $functionName = 'users';

	/**
	 * @var \modules\smarty_based_template_processor\lib\TemplateProcessor
	 */
	private $templateProcessor;

	public function respond()
	{
		$this->requireFiles();

		$this->templateProcessor = \App()->getTemplateProcessor();

		$this->mapActionToMethod
			(
				array
				(
					'ACTIVATE' => array($this, 'activateUser'),
					'DEACTIVATE' => array($this, 'deactivateUser'),
					'DELETE' => array($this, 'deleteUser'),
					'SEND ACTIVATION LETTER' => array($this, 'sendActivationLetter'),
					'SEND LETTER' => array($this, 'sendLetter'),
					'MAKE TRUSTED' => array($this, 'makeTrusted'),
					'MAKE UNTRUSTED' => array($this, 'makeUntrusted'),
					'CHANGE USER GROUP' => array($this, 'changeUserGroup'),
				)
			);
		if (!empty($_REQUEST['returnBackUri'])) {
			throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . $_REQUEST['returnBackUri']);
		}
		$this->displayForm();
		$this->displayTable();
	}

	private function activateUser($users_sids)
	{
		foreach ($users_sids as $user_sid => $value) {
			$username = \App()->UserManager->getUserNameByUserSID($user_sid);
			\App()->UserManager->activateUserByUserName($username);
		}
		\App()->SuccessMessages->addMessage('USERS_ACTIVATED');
	}

	private function deactivateUser($users_sids)
	{
		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\users\apps\AdminPanel\IDeactivateUsersValidator');
		foreach ($validators as $validator) {
			$validator->setUserSids($users_sids);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;

		foreach ($users_sids as $user_sid => $value) {
			$username = \App()->UserManager->getUserNameByUserSID($user_sid);
			\App()->UserManager->deactivateUserByUserName($username);
		}
		\App()->SuccessMessages->addMessage('USERS_DEACTIVATED');
	}

	private function deleteUser($users_sids)
	{
		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\users\apps\AdminPanel\IDeleteUsersValidator');
		foreach ($validators as $validator) {
			$validator->setUserSids($users_sids);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;

		foreach ($users_sids as $user_sid => $value) {
			$onDeleteUserActions = new \core\ExtensionPoint('modules\users\IOnDeleteUserAction');
			foreach ($onDeleteUserActions as $onDeleteUserAction) {
				$onDeleteUserAction->setUserSid($user_sid);
				$onDeleteUserAction->perform();
			}

			\App()->ObjectMother->createUserEraser($user_sid)->perform();
		}
		\App()->SuccessMessages->addMessage('USERS_DELETED');
	}

	private function sendActivationLetter($users_sids)
	{
		$failedUserSids = [];
		foreach ($users_sids as $user_sid => $value) {
			if (!\App()->UserManager->sendUserActivationLetter($user_sid))
				$failedUserSids[] = $user_sid;
		}
		if (empty($failedUserSids)) {
			\App()->SuccessMessages->addMessage('ALL_EMAILS_SENT');
		} else {
			$failedUsers = [];
			foreach ($failedUserSids as $userSid)
				$failedUsers[] = \App()->UserManager->getUserNameByUserSID($userSid);

			\App()->ErrorMessages->addMessage('FAILED_TO_SEND_EMAILS', array('failedUsers' => $failedUsers));
		}
	}

    private function sendLetter($users_sids)
    {
        $emailTemplate = '{subject}' . \App()->Request['subject'] . '{/subject}';
        $emailTemplate .= '{message}' . \App()->Request['letter_body'] . '{/message}';
        $replyTo = \App()->SettingsFromDB->getSettingByName('system_email_reply_to');
        foreach ($users_sids as $user_sid => $value)
        {
             $userInfo = \App()->UserManager->getUserInfoBySID($user_sid);
            \App()->EmailService->send($userInfo['email'], 'string:' . $emailTemplate, [], $replyTo);
        }
        \App()->SuccessMessages->addMessage('EMAIL_SENT');
    }

	private function makeTrusted($users_sids)
	{
		foreach ($users_sids as $user_sid => $value) {
			\App()->UserManager->makeTrusted($user_sid);
		}
		\App()->SuccessMessages->addMessage('USERS_MADE_TRUSTED');
	}

	private function makeUntrusted($users_sids)
	{
		foreach ($users_sids as $user_sid => $value) {
			\App()->UserManager->makeUntrusted($user_sid);
		}
		\App()->SuccessMessages->addMessage('USERS_MADE_UNTRUSTED');
	}

	private function changeUserGroup($usersSids)
	{
		$groupId = isset($_REQUEST['groupId']) ? $_REQUEST['groupId'] : null;
		try {

			array_map(function ($userSid) use ($groupId) {
				\App()->UserManager->changeUserGroupByUserSid($userSid, $groupId);
			}, $usersSids);

			$afterUserGroupChangeActions = new \core\ExtensionPoint('modules\users\apps\AdminPanel\IAfterUserGroupChangeAction');
			foreach ($afterUserGroupChangeActions as $afterUserGroupChangeAction) {
				$afterUserGroupChangeAction->setUserSids($usersSids);
				$afterUserGroupChangeAction->perform();
			}

			\App()->SuccessMessages->addMessage('USER_GROUP_CHANGED');
		} catch (\Exception $e) {
			$this->templateProcessor->assign("errors", array($e->getMessage() => $e->getMessage()));
		}
	}

	private function displayForm()
	{
		$user = \App()->UserManager->createUser([], null);
		$user->addProperty(array
		(
			'id' => 'user_group',
			'type' => 'list',
			'value' => '',
			'is_system' => true,
			'list_values' => \App()->UserGroupManager->getAllUserGroupsIDsAndCaptions(),
		));
		$user->addProperty(array
		(
			'id' => 'with_membership_plan',
			'column_name' => 'membership_plan_sid',
			'type' => 'list',
			'value' => '',
			'is_system' => true,
			'list_values' => $this->getMembershipPlansListValues(),
		));
		$user->addProperty(array
		(
			'id' => 'without_membership_plan',
			'column_name' => 'membership_plan_sid',
			'type' => 'boolean',
			'value' => '',
			'is_system' => true,
		));
		$user->addProperty(array
		(
			'id' => 'registration_date',
			'type' => 'date',
			'value' => '',
			'is_system' => true,
		));

		$templateFormProperties = [];
		$extenders = new \core\ExtensionPoint('modules\users\apps\AdminPanel\ISearchFormPropertiesExtender');
		foreach ($extenders as $extender) {
			$user->addProperty($extender->getPropertyInfo());
			$templateFormProperties[] = $extender->getPropertyInfo();
		}

		$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($user);
		$requestReflection = \App()->ObjectMother->createReflectionFactory()->createHashtableReflector($this->getSearch()->getRequest());
		$search_form_builder->setRequestData($requestReflection);
		$search_form_builder->registerTags($this->templateProcessor);
		$this->templateProcessor->assign('templateFormProperties', $templateFormProperties);
		$this->templateProcessor->display("user_search_form.tpl");
	}

	private function getMembershipPlansListValues()
	{
		return \App()->MembershipPlanManager->getAllMembershipPlansIDsAndCaptions();
	}

	private function displayTable()
	{

		$search = $this->getSearch();
		$this->setObjectsPerPage($search);
		$this->setPage($search);
		$this->setSortingFields($search);
		$this->templateProcessor->assign("userGroupOptions", \App()->UserGroupManager->getAllUserGroupsIDsAndCaptions());
		$this->templateProcessor->assign("search", new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
		$this->templateProcessor->assign("users", $this->getUsers($search));
		$this->templateProcessor->assign('sortingFields', $this->getSortingFields());
		$this->templateProcessor->assign("checkedUsers", isset($_REQUEST['users']) ? $_REQUEST['users'] : []);
		$this->templateProcessor->assign("userMassActions", new \core\ExtensionPoint('modules\users\lib\User\IUserMassAction'));
		$this->templateProcessor->display("users.tpl");
		$this->saveSearchToSession($search);
	}

	private function getSortingFields()
	{
		$properties = $this->getModelObject()->getDetails()->getProperties();
		$sortableProperties = array_filter($properties, array($this, 'isPropertySortable'));
		$sortingFields = array_map(create_function('$property', 'return $property->getCaption();'), $sortableProperties);
		return $sortingFields;
	}

	private function isPropertySortable(\lib\ORM\ObjectProperty $property)
	{
		$systemPropertiesToInclude = array('username', 'email', 'user_group', 'registration_date', 'active', 'trusted_user');
		$typesToExclude = array('text', 'picture', 'object');
		if ($property->isSystem()) {
			$sortable = in_array($property->getID(), $systemPropertiesToInclude);
		} else {
			$sortable = !in_array($property->getType(), $typesToExclude);
		}
		return $sortable;
	}

	private function saveSearchToSession($search)
	{
		$ss = serialize($search);
		\App()->Session->setValue(self::$CurrentSearchId, $ss);
	}

	private function getUsers($search)
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}

	private function setPage($search)
	{
		if (isset($_REQUEST['page'])) $search->setPage(intval($_REQUEST['page']));
	}

	private function setObjectsPerPage($search)
	{
		if (isset($_REQUEST['items_per_page'])) $search->setObjectsPerPage(intval($_REQUEST['items_per_page']));
	}

	private function setSortingFields($search)
	{
		if (isset($_REQUEST['sorting_fields']) && is_array($_REQUEST['sorting_fields']))
			$search->setSortingFields($_REQUEST['sorting_fields']);
	}

	private function getSearch()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore' && \App()->Session->getValue(self::$CurrentSearchId)) {
			$search = unserialize(\App()->Session->getValue(self::$CurrentSearchId));
			$_REQUEST = array_merge($search->getRequest(), $_REQUEST);
		} else {
			$search = new \lib\ORM\SearchEngine\Search();
			$search->setPage(1);
			$search->setObjectsPerPage(self::$DEFAULT_USERS_PER_PAGE);
		}
		$search->setRequest($_REQUEST);
		$search->setDB(\App()->DB);
		$search->setModelObject($this->getModelObject());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		$userManagerToRowMapperAdapter = new \modules\users\lib\UserManagerToRowMapperAdapter(\App()->UserManager);
		$userExtraPropertySetters = new \core\ExtensionPoint('modules\users\apps\AdminPanel\IUserExtraPropertySetterOnSearchUser');
		foreach ($userExtraPropertySetters as $userExtraPropertySetter) {
			$userManagerToRowMapperAdapter->addUserExtraPropertySetter($userExtraPropertySetter);
		}
		$search->setRowMapper($userManagerToRowMapperAdapter);

		return $search;
	}

	private $model = null;

	private function getModelObject()
	{
		if (is_null($this->model)) {
			$this->model = \App()->UserManager->createUser([], $this->getUserGroupSid());
			$this->model->addProperty(array
				(
					'id' => 'with_membership_plan',
					'type' => 'integer',
					'is_required' => false,
					'is_system' => true,
					'table_name' => 'membership_plan_contracts',
					'column_name' => 'membership_plan_sid',
					'join_condition' => array('key_column' => 'contract_sid', 'foriegn_column' => 'sid')
				)
			);
			$this->model->addProperty(array
				(
					'id' => 'without_membership_plan',
					'type' => 'integer',
					'is_required' => false,
					'is_system' => true,
					'table_name' => 'membership_plan_contracts',
					'column_name' => 'membership_plan_sid',
					'join_condition' => array('key_column' => 'contract_sid', 'foriegn_column' => 'sid')
				)
			);

			$extenders = new \core\ExtensionPoint('modules\users\apps\AdminPanel\ISearchFormPropertiesExtender');

			foreach ($extenders as $extender) {
				$this->model->addProperty($extender->getPropertyInfo());
			}

		}
		return $this->model;
	}

	private function getUserGroupSid()
	{
		return null; // we seach only by common fields
	}

	private function requireFiles()
	{
	}

	private function mapActionToMethod($map)
	{
		if (!isset($_REQUEST['action'], $_REQUEST['users'])) return;
		$users_sids = $_REQUEST['users'];
		$action = strtoupper($_REQUEST['action']);
		if (isset($map[$action])) {
			call_user_func($map[$action], $users_sids);
		}
	}

	private static $DEFAULT_USERS_PER_PAGE = 20;
	private static $CurrentSearchId = 'admin_user_search';

	public static function getOrder()
	{
		return 300;
	}

	public function getCaption()
	{
		return "Manage Users";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('users');
	}

	public function getHighlightUrls()
	{
		return array
		(
            \App()->PageRoute->getPageURLById('edit_user'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'add_user'),
		);
	}
}

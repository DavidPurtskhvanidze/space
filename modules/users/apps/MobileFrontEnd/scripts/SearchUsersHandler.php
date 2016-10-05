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


namespace modules\users\apps\MobileFrontEnd\scripts;

class SearchUsersHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'Search users';
	protected $moduleName = 'users';
	protected $functionName = 'search_users';
	protected $parameters = array('user_group_id', 'search_form_template', 'fields', 'results_template');

	private $templateProcessor;
	private $userGroupSid;
	private $model;
	private static $DEFAULT_USERS_PER_PAGE = 10;
	private static $CurrentSearchId = 'user_search';
	
	public function respond()
	{
		$this->templateProcessor = \App()->getTemplateProcessor();
		$this->displayForm();
		$this->displayTable();
	}
	private function displayForm()
	{
		$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($this->getModelObject());
		$search_form_builder->setRequestData(\App()->ObjectMother->createRequestReflector());
		$search_form_builder->registerTags($this->templateProcessor);
		
		$fieldsToDisplay = isset($_REQUEST['fields']) ? preg_split("/[\s,]+/", $_REQUEST['fields']) : array();
		$formFieldsFilter = \App()->ObjectMother->createFormFieldsFilter($fieldsToDisplay);
		
		$formFields = $search_form_builder->getFormFieldsInfo();
		$formFields = array_filter($formFields, array(&$formFieldsFilter, 'filter'));
		
		$this->templateProcessor->assign("form_fields", $formFields);
		$this->templateProcessor->display(isset($_REQUEST['search_form_template']) ? $_REQUEST['search_form_template'] : "user_search_form.tpl");
	}
	private function displayTable()
	{

		if (!is_null($this->getUserGroupSid())) $_REQUEST['user_group_sid']['equal'] = $this->getUserGroupSid();
		
		$search = $this->getSearch();
		$this->setObjectsPerPage($search);
		$this->setPage($search);
		$this->templateProcessor->assign("search", new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
		$this->templateProcessor->assign("users", $this->getUsers($search));
		$this->templateProcessor->display(isset($_REQUEST['results_template']) ? $_REQUEST['results_template'] : "user_search_results.tpl");
		$this->saveSearchToSession($search);
	}
	private function getSearch()
	{
						$_REQUEST['active']['equal'] = 1;
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore')
		{
			$search = unserialize(\App()->Session->getValue(self::$CurrentSearchId));
			$search->setRequest(array_merge($search->getRequest(), $_REQUEST)); // i need to incorporate new parameters, including sorting fields and order
		}
		else
		{
			$search = new \lib\ORM\SearchEngine\Search();
			$search->setRequest($_REQUEST);
			$search->setPage(1);
			$search->setObjectsPerPage(self::$DEFAULT_USERS_PER_PAGE);
		}
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\users\lib\UserManagerToRowMapperAdapter(\App()->UserManager));
		$search->setModelObject($this->getModelObject());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		return $search;
	}
	private function saveSearchToSession($search)
	{
		$ss = serialize($search);
		\App()->Session->setValue(self::$CurrentSearchId, $ss);
	}
	private function setPage($search)
	{
		if (isset($_REQUEST['page'])) $search->setPage(intval($_REQUEST['page']));
	}
	private function setObjectsPerPage($search)
	{
		if (isset($_REQUEST['items_per_page'])) $search->setObjectsPerPage(intval($_REQUEST['items_per_page']));
	}
	private function getUsers($search)
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}
	private function getModelObject()
	{
		if (is_null($this->model))
		{
			$this->model = \App()->UserManager->createUser(array(), $this->getUserGroupSid());
			$this->model->addProperty
			(
				array
				(
					'id' => 'user_group',
					'caption' => 'User Group',
					'type' => 'list',
					'value' => '',
					'is_system' => true,
					'list_values' => \App()->UserGroupManager->getAllUserGroupsIDsAndCaptions(),
				)
			);
		}
		return $this->model;
	}
	private function getUserGroupSid()
	{
		if (is_null($this->userGroupSid))
		{
			$userGroupId = isset($_REQUEST['user_group_id']) ? $_REQUEST['user_group_id'] : null;
			$this->userGroupSid = \App()->UserGroupManager->getUserGroupSIDByID($userGroupId);
		}
		return $this->userGroupSid;
	}
}

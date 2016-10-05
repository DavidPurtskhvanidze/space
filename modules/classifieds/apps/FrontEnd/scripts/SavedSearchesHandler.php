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


namespace modules\classifieds\apps\FrontEnd\scripts;

class SavedSearchesHandler extends \apps\FrontEnd\ContentHandlerBase
{
    /**
     * @var \modules\classifieds\lib\SavedSearch\ISavedSearchStorage
     */
    private  $storage;
    protected $displayName = 'Saved Searches';
	protected $moduleName = 'classifieds';
	protected $functionName = 'saved_searches';

	private $errors = array();

	public function respond()
	{
		$this->storage = \App()->SavedSearchManager->getSavedSearchStorage();
		$this->mapActionToMethod
		(
			array
			(
				'DELETE' => array($this->storage, 'deleteSearch'),
				'DISABLE_NOTIFY' => array($this->storage, 'disableAutonotification'),
				'ENABLE_NOTIFY' => array($this->storage, 'enbaleAutonotification'),
			)
		);
		$this->displayPage();
	}

	private function displayPage()
	{				
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("errors", $this->errors);
		$template_processor->assign("saved_searches", $this->storage->getSearches());
		$template_processor->assign("user_logged_in", \App()->UserManager->isUserLoggedIn());
		$template_processor->display("saved_searches.tpl");
	}

	private function mapActionToMethod($map)
	{
		if (!isset($_REQUEST['action'])) return;
		if (!isset($_REQUEST['search_id'])) return;
		$id =  $_REQUEST['search_id'];
		$action = strtoupper($_REQUEST['action']);

		if ($action == 'ENABLE_NOTIFY')
		{
			$currentUserInfo = \App()->UserManager->getCurrentUserInfo();
			if (is_null($currentUserInfo['email']))
			{
				$this->errors[] = "EMPTY_USER_EMAIL";
				return;
			}
		}
		if (isset($map[$action]))
		{
			call_user_func($map[$action], $id);
		}
	}
}

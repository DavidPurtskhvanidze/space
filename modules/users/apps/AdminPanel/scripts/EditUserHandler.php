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

class EditUserHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'edit_user';

	public function respond()
	{

		$template_processor = \App()->getTemplateProcessor();
		
		if (isset($_REQUEST['current_username']))
            $username = $_REQUEST['current_username'];
		elseif (isset($_REQUEST['username']))
            $username = $_REQUEST['username'];
		else
            $username = null;

        $user_info = (!is_null($username)) ? \App()->UserManager->getUserInfoByUserName($username) : null;

		if (!is_null($user_info))
		{
			$user_info = array_merge($user_info, $_REQUEST);

			$user = \App()->UserManager->createUser($user_info, $user_info['user_group_sid']);
			$sampleUserObject = \App()->UserManager->createUser($user_info, $user_info['user_group_sid']);
			$user->makePropertyNotRequired("password");
			
			if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_contract')
			{
				$contractId = $user->getContractID();
				\App()->ContractManager->deleteContract($contractId);
				$user->setContractID(null);
				$user->deleteProperty('password');
				\App()->UserManager->saveUser($user);
				throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?username=$username&userContractDeleted=1");
			}

			array_map(array($user,'deleteProperty'),array('user_group', 'user_group_sid', 'registration_date', 'group', 'active', 'trusted_user', 'sid'));

			$extraProperties = new \core\ExtensionPoint('modules\users\apps\AdminPanel\IEditUserFormExtraProperty');
			foreach ($extraProperties as $property)
			{
				/**
				 * @var \modules\users\apps\AdminPanel\IEditUserFormExtraProperty $property
				 */

				$property->setUser($user);
				$user->addProperty($property->getInfo());
			}

			$edit_user_form = new \lib\Forms\Form($user);
			$form_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save_info');

			if ($form_submitted && $edit_user_form->isDataValid())
			{
				$password_value = $user->getPropertyValue('password');
				if (empty($password_value['original'])) $user->deleteProperty('password');
				\App()->UserManager->saveUser($user);
				throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('users') . '?action=restore');
			}
			else
			{
				$edit_user_form->registerTags($template_processor);
				$template_processor->assign("user", \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($sampleUserObject));
				$template_processor->assign("numberOfListings", \App()->ListingManager->getListingsCountByUserSID($user->getSID()));
				$template_processor->assign("form_fields", $edit_user_form->getFormFieldsInfo());
				$template_processor->assign("userContractDeleted", isset($_REQUEST['userContractDeleted']));
				$template_processor->assign("userContractId", $user->getContractID());
				$template_processor->assign("userGroupInfo", \App()->UserGroupManager->getUserGroupInfoBySID($user->getUserGroupSID()));
				$template_processor->assign("userGroupOptions", \App()->UserGroupManager->getAllUserGroupsIDsAndCaptions());
				$template_processor->display("edit_user.tpl");
			}
		}
        else
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            echo 'User not found';
        }
	}
}

<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\AdminPanel\scripts;

class AdminpswdHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\miscellaneous\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'adminpswd';

	public function respond()
	{
		$errors = array();
		$usernameAndPasswordChanged = false;
		$form_items = array
		(
			'old_name' => array ('type' => 'static', 'caption' => 'Old Name', 'value' => \App()->Session->getValue('username')),
			'new_name' => array ('type' => 'text', 'caption' => 'New Name', 'value' => \App()->Session->getValue('username')),
			'old_password' => array ('type' => 'password', 'caption' => 'Old Password', 'value' => ''),
			'new_password' => array ('type' => 'password', 'caption' => 'New Password', 'value' => ''),
			'new_password_confirm' => array ('type' => 'password', 'caption' => 'Confirm Password', 'value' => ''),
		);

		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'change_admin_account')
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\miscellaneous\apps\AdminPanel\IChangeAdminPasswordValidator');
			foreach ($validators as $validator)
			{
				$canPerform &= $validator->isValid();
			}
			if ($canPerform)
			{
				$oldUsername = \App()->Session->getValue('username');
				$newUsername = \App()->Request['new_name'];
				$oldPassword = \App()->Request['old_password'];
				$newPassword = \App()->Request['new_password'];
				$newPasswordConfirm = \App()->Request['new_password_confirm'];

				if (empty($oldPassword))
				{
					\App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => 'Old Password'));
				}
					elseif (empty($newPassword))
				{
					\App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => 'New Password'));
				}
				elseif (($oldUsername == $newUsername) && ($oldPassword == $newPassword))
				{
					\App()->ErrorMessages->addMessage('SAME_OLD_AND_NEW_USERNAME_AND_PASSWORD');
				}
				elseif ($newPassword != $newPasswordConfirm)
				{
					\App()->ErrorMessages->addMessage('NOT_CONFIRMED', array('fieldCaption' => 'New Password'));
				}
				else
				{
					$sql = "UPDATE `core_administrator` SET `username` = ?s, `password` = PASSWORD(?s) WHERE `username` = ?s AND `password` = PASSWORD(?s)";
					if (!\App()->DB->query($sql, $newUsername, $newPassword, $oldUsername, $oldPassword))
					{
						\App()->ErrorMessages->addMessage('CANNOT_EXECUTE_SQL_QUERY');
					}
					elseif (\App()->DB->affected_rows() != 1)
					{
						\App()->ErrorMessages->addMessage('WRONG_OLD_PASSWORD');
					}
					else
					{
						$usernameAndPasswordChanged = true;
						$_SESSION['username'] = $newUsername;
						\App()->Session->setValue('username', $newUsername);
						$form_items['old_name']['value'] = \App()->Session->getValue('username');
						$form_items['new_name']['value'] = \App()->Session->getValue('username');
						unset ($_REQUEST);
					}
				}
			}
		}

		foreach ($form_items as $item_name => $item_params)
		{
			if (($item_params['type'] != 'password') && (isset ($_REQUEST[$item_name])))
				$item_params['value'] = $_REQUEST[$item_name];
		}

		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign ("usernameAndPasswordChanged", $usernameAndPasswordChanged);
		$template_processor->assign ("errors", $errors);
		$template_processor->assign ("form_items", $form_items);
		$template_processor->display ("adminpswd.tpl");
	}

	public function getCaption()
	{
		return "Admin Password";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('adminpswd');
	}

	public function getHighlightUrls()
	{
		return array();
	}

	public static function getOrder()
	{
		return 100;
	}
}

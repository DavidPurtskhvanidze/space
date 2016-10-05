<?php
/**
 *
 *    Module: import_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19788, 2016-06-17 13:19:38
 *
 *    This file is part of the 'import_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_users\lib;

class UserValidator
{
	private $errors = array();

	/**
	 * @param \modules\users\lib\User\User $user
	 * @return bool
	 */
	function isValid($user)
	{
		$this->errors = array();
		$userGroupSid = $user->getUserGroupSID();
		if (empty($userGroupSid))
		{
			$tp = \App()->getTemplateProcessor();
			$tp->assign('fieldCaption', 'User Group');
			$this->errors[] = $tp->fetch("miscellaneous^error_messages/undefined_user_group.tpl");
		}
		foreach ($user->getProperties() as $property)
		{
			if (!$property->isValid())
			{
				$this->errors[] = $property->getValidationErrorMessage();
			}
		}
		return empty($this->errors);
	}

	public function getErrors()
	{
		return $this->errors;
	}
}

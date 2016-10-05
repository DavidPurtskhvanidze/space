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


namespace modules\classifieds\apps\AdminPanel\scripts;

class DeleteCategoryHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'delete_category';

	public function respond()
	{
		$categorySid = \App()->Request['sid'];

		if (!is_null($categorySid))
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\classifieds\apps\AdminPanel\IDeleteCategoryValidator');
			foreach ($validators as $validator)
			{
				$validator->setCategorySid($categorySid);
				$canPerform &= $validator->isValid();
			}

			$parentSid = \App()->CategoryManager->getParentSID($categorySid);

			if ($canPerform)
			{
                $beforeActions = new \core\ExtensionPoint('modules\classifieds\lib\IOnBeforeDeleteCategoryAction');
                foreach($beforeActions as $action)
                {
                    $action->setCategorySid($categorySid);
                    $action->perform();
                }

				\App()->CategoryManager->deleteCategoryBySID($categorySid);
			}

			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('edit_category') . '?sid=' . $parentSid);
		}
		else
		{
			echo 'The system  cannot proceed as Category SID is not set';
		}
	}
}

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

class ImportListDataHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'import_list_data';

	public function respond()
	{
		$fieldSid = \App()->Request['field_sid'];
		$fieldInfo = \App()->UserProfileFieldManager->getInfoBySID($fieldSid);
		
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign(
			'userGroup', 
			array(
				'sid' => $fieldInfo['user_group_sid'],
				'name' => \App()->UserGroupManager->getUserGroupNameBySID($fieldInfo['user_group_sid']),
			)
		);
		$templateProcessor->assign("field", $fieldInfo);
		$templateProcessor->assign("field_sid", $fieldSid);

		if ($fieldInfo['type'] == 'list')
		{
			if (\App()->Request['action'] == 'import')
			{
				$this->validateData();
				if (\App()->ErrorMessages->isEmpty())
				{
					if (\App()->Request['file_format'] == 'excel')
					{
						$importedFile = new \modules\miscellaneous\lib\ImportedExcelFile();
					}
					else
					{
						$importedFile = new \modules\miscellaneous\lib\ImportedCSVFile();
					}
					$importedFile->setFileName($_FILES['imported_list_file']['tmp_name']);
					$importedData = $importedFile->getTable();
					$count = 0;
					$manager = new \modules\users\lib\UserProfileField\UserProfileFieldListItemManager();
					for ($i = (\App()->Request['start_line'] - 1); $i < count($importedData); $i++)
					{
						if (is_array($importedData[$i]))
						{
							$manager->addListItem($fieldSid, array_pop($importedData[$i]));
							$count++;
						}
					}
					$templateProcessor->assign("count", $count);
					$templateProcessor->display("import_list_data_statistics.tpl");
					return;
				}
			}
			$templateProcessor->display("import_list_data.tpl");
		}
		else
		{
			echo 'Invalid List SID is specified';
		}
	}

	private function validateData()
	{
		if (empty($_FILES['imported_list_file']['name']))
		{
			\App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => 'File'));
		}
		$startLine = \App()->Request['start_line'];
		if (empty($startLine))
		{
			\App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => 'Start Line'));
		}
		elseif (!is_numeric($startLine) || !is_int($startLine + 0))
		{
			\App()->ErrorMessages->addMessage('NOT_INTEGER_VALUE', array('fieldCaption' => 'Start Line'));
		}
	}
}

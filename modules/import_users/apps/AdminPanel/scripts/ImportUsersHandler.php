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


namespace modules\import_users\apps\AdminPanel\scripts;

use apps\AdminPanel\ContentHandlerBase;
use lib\DataTransceiver\TransceiveFailedException;
use lib\File\FileException;
use lib\File\Upload;
use modules\import_users\lib\ImportUsersConfigRequestData;
use modules\import_users\lib\ImportUsersFactory;
use modules\users\apps\AdminPanel\IMenuItem;

class ImportUsersHandler extends ContentHandlerBase implements IMenuItem
{
	protected $moduleName = 'import_users';
	protected $functionName = 'import_users';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->display('import_users.tpl');

		if (\App()->Request['action'] == 'import')
		{
			$importUsersConfig = new ImportUsersConfigRequestData();

            try
            {
                $uploader = new Upload(\App()->UploadedFiles['import_file']['tmp_name'], \App()->UploadedFiles['import_file']['name']);
                $file = $uploader->move(PATH_TO_ROOT . \App()->SystemSettings['TempFilesDir'], \App()->UploadedFiles['import_file']['name']);

                $importUsersConfig->setFilePath($file->getPathname());
                $factory = new ImportUsersFactory();
                $import = $factory->createDataTransceiver($importUsersConfig);
                $import->perform();
                $import->finalize();
                $template_processor->assign('log', $import->getLog());
                $template_processor->display('import_users_log.tpl');
                return;
            }
            catch (FileException $e)
            {
                \App()->ErrorMessages->addMessage('FILE_UPLOAD_ERROR', ['message' => $e->getMessage()]);
            }
            catch (TransceiveFailedException $e)
            {
                \App()->ErrorMessages->addMessage($e->getMessage());
            }
		}

		$template_processor->assign('user_groups', \App()->UserGroupManager->getAllUserGroupsInfo());
		$template_processor->display('import_users_form.tpl');
	}

	public static function getOrder()
	{
		return 400;
	}

	public function getCaption()
	{
		return "Import Users";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array
		(
		);
	}
}

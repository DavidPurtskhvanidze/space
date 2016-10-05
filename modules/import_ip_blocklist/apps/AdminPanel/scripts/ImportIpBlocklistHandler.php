<?php
/**
 *
 *    Module: import_ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19786, 2016-06-17 13:19:33
 *
 *    This file is part of the 'import_ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_ip_blocklist\apps\AdminPanel\scripts;

use apps\AdminPanel\ContentHandlerBase;
use lib\DataTransceiver\TransceiveFailedException;
use lib\File\FileException;
use lib\File\Upload;
use modules\import_ip_blocklist\lib\ImportIpBlocklistConfigRequestData;
use modules\import_ip_blocklist\lib\ImportIpBlocklistFactory;

class ImportIpBlocklistHandler extends ContentHandlerBase
{
	protected $moduleName = 'import_ip_blocklist';
	protected $functionName = 'import_blocklist';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->display('import_ip_blocklist.tpl');

		if (\App()->Request['action'] == 'import')
		{
			$importConfig = new ImportIpBlocklistConfigRequestData();

            try
            {
                $uploader = new Upload(\App()->UploadedFiles['import_file']['tmp_name'], \App()->UploadedFiles['import_file']['name']);
                $file = $uploader->move(PATH_TO_ROOT . \App()->SystemSettings['TempFilesDir'], \App()->UploadedFiles['import_file']['name']);

                $importConfig->setFilePath($file->getPathname());
                $factory = new ImportIpBlocklistFactory();
                $import = $factory->createDataTransceiver($importConfig);
                $import->perform();
                $import->finalize();
                $template_processor->assign('log', $import->getLog());
                $template_processor->display('import_ip_blocklist_log.tpl');
                return;
            }
            catch(FileException $e)
            {
                \App()->ErrorMessages->addMessage('FILE_UPLOAD_ERROR', ['message' => $e->getMessage()]);
            }
            catch (TransceiveFailedException $e)
            {
                \App()->ErrorMessages->addMessage($e->getMessage());
            }
		}

		$template_processor->display('import_ip_blocklist_form.tpl');
	}

}

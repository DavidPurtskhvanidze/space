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

use apps\AdminPanel\ContentHandlerBase;
use lib\DataTransceiver\TransceiveFailedException;
use lib\File\FileException;
use lib\File\Upload;
use modules\classifieds\lib\ImportListDataConfigRequestDataFactory;
use modules\classifieds\lib\ImportListDataFactory;
use modules\classifieds\lib\MultiListValuesLimitExceededException;

class ImportListDataHandler extends ContentHandlerBase
{
    protected $moduleName = 'classifieds';
    protected $functionName = 'import_list_data';

    public function respond()
    {
        $templateProcessor = \App()->getTemplateProcessor();
        $fieldSid = (\App()->Request['field_sid']) ? \App()->Request['field_sid'] : $_GET['field_sid'];
        $fieldInfo = \App()->ListingFieldManager->getInfoBySID($fieldSid);
        $templateProcessor->assign("field", $fieldInfo);
        $templateProcessor->assign("field_sid", $fieldSid);
        $templateProcessor->assign("ancestors", array_reverse(\App()->CategoryTree->getAncestorsInfo($fieldInfo['category_sid'])));
        $templateProcessor->display('import_list_data.tpl');

        if (\App()->Request['action'] == 'import')
        {
            $importConfigFactory = new ImportListDataConfigRequestDataFactory($fieldInfo);
            $importConfig = $importConfigFactory->getImportConfig();

            try
            {
                $uploader = new Upload(\App()->UploadedFiles['import_file']['tmp_name'], \App()->UploadedFiles['import_file']['name']);
                $file = $uploader->move(PATH_TO_ROOT . \App()->SystemSettings['TempFilesDir'], \App()->UploadedFiles['import_file']['name']);

                $importConfig->setFilePath($file->getPathname());
                $factory = new ImportListDataFactory();

                $import = $factory->createDataTransceiver($importConfig);

                try
                {
                    $import->perform();
                }
                catch (MultiListValuesLimitExceededException $e)
                {
                    $templateProcessor->assign('import_error_multilist_values_limit_exceeded', true);
                }

                $import->finalize();
                $templateProcessor->assign('log', $import->getLog());

            }
            catch (FileException $e)
            {
                \App()->ErrorMessages->addMessage('FILE_UPLOAD_ERROR', ['message' => $e->getMessage()]);
            }
            catch (TransceiveFailedException $e)
            {
                \App()->ErrorMessages->addMessage($e->getMessage());
            }
            $templateProcessor->display('import_list_data_log.tpl');
            return;
        }

        $templateProcessor->display('import_list_data_form.tpl');
    }
}

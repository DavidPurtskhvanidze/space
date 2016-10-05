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

use apps\AdminPanel\ContentHandlerBase;
use core\ExtensionPoint;
use lib\File\FileException;
use lib\File\Upload;
use lib\Http\RedirectException;
use modules\miscellaneous\apps\AdminPanel\IMenuItem;

class SettingsHandler extends ContentHandlerBase implements IMenuItem
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'settings';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();

		$watermarkUploadStatus = null;

		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save')
		{
            $this->handlePictureUpload();
            $this->handleUnderConstruction();

			unset($_REQUEST['action']);
			\App()->SettingsFromDB->updateSettings($_REQUEST);
            \App()->selfHttpRequest(['SYSCOMMAND' => 'CLEAR_MODULE_FUNCTION_INFO_CACHE']);
			throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?action=restore");
		}

		$settings = \App()->SettingsFromDB->getSettings();

		$template_processor->assign("settings", $settings);
		$template_processor->assign("watermarkUploadStatus", $watermarkUploadStatus);
		$template_processor->assign("picturesDir", PATH_TO_ROOT . \App()->SystemSettings['PicturesDir']);


		$pages = new ExtensionPoint('modules\miscellaneous\ISystemSettingPage');

        $template_processor->assign("pages", $pages);
		$template_processor->display("settings.tpl");
	}

	public function getCaption()
	{
		return "System Settings";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('settings');
	}

	public function getHighlightUrls()
	{
		return array();
	}

	public static function getOrder()
	{
		return 200;
	}

    protected function handleUnderConstruction()
    {
        if (\App()->Request['under_construction_mode'])
        {
           \App()->FileSystem->putContentsToFile(PATH_TO_ROOT . 'files/under_mod.txt', $_SERVER['REMOTE_ADDR'] . ',' . $_SERVER['SERVER_ADDR'] );
        }
        else
        {
            \App()->FileSystem->deleteFile(PATH_TO_ROOT . 'files/under_mod.txt');
        }
    }

    protected function handlePictureUpload()
    {
        $pictures = [
            'watermark_picture' => [
                'formats' => ['gif', 'jpg', 'jpeg', 'png'],
                'delete_action' => 'delete_watermark',
            ],
            'favicon_icon' => [
                'formats' => ['ico', 'png', 'gif'],
                'delete_action' => 'delete_favicon',
            ],
            'main_logo' => [
                'formats' => ['ico', 'png', 'gif', 'jpg', 'jpeg'],
                'delete_action' => 'delete_main_logo',
            ],
            'fixed_top_menu_logo' => [
                'formats' => ['ico', 'png', 'gif', 'jpg', 'jpeg'],
                'delete_action' => 'delete_fixed_top_menu_logo',
            ],
            'mobile_logo' => [
                'formats' => ['ico', 'png', 'gif', 'jpg', 'jpeg'],
                'delete_action' => 'delete_mobile_logo',
            ],
        ];

        try
        {
            $uplodedFiles = \App()->UploadedFiles;
            foreach($pictures as $fileId => $picture)
            {

                if (empty($uplodedFiles[$fileId]['name']))
                {
                    if (\App()->Request[$picture['delete_action']])
                    {
                        $savedFileName = \App()->SettingsFromDB->getSettingByName($fileId);
                        \App()->FileSystem->deleteFile(PATH_TO_ROOT . \App()->SystemSettings['PicturesDir'] . $savedFileName);
                        \App()->Request[$fileId] = '';
                    }
                    continue;
                }


                $uploadedFile = $uplodedFiles[$fileId];
                $supportedExtensions = $picture['formats'];
                $uploadedFileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
                if (in_array($uploadedFileExtension, $supportedExtensions))
                {
                    $fileName = $fileId . '.' . $uploadedFileExtension;
                    $uploader = new Upload($uploadedFile['tmp_name'], $fileName);
                    $file = $uploader->move(PATH_TO_ROOT . \App()->SystemSettings['PicturesDir'], $fileName);
                    \App()->Request[$fileId] = $file->getFilename();
                }
                else
                {
                    \App()->ErrorMessages->addMessage('UNSUPPORTED_FILE_TYPE', array('supportedTypes' => $supportedExtensions, 'fileId' => $fileId, 'fileName' => $uploadedFile['name']));
                }
            }
        }
        catch (FileException $e)
        {
            if ($e->getMessage() != 'UPLOAD_ERR_NO_FILE')
            {
                \App()->ErrorMessages->addMessage('UPLOAD_FILE_ERROR', $e->getMessage());
            }
        }
    }
}

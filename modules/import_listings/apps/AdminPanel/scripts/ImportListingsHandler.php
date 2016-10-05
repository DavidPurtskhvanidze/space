<?php
/**
 *
 *    Module: import_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19787, 2016-06-17 13:19:36
 *
 *    This file is part of the 'import_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_listings\apps\AdminPanel\scripts;

use apps\AdminPanel\ContentHandlerBase;
use core\ExtensionPoint;
use lib\DataTransceiver\TransceiveFailedException;
use lib\File\FileException;
use lib\File\Upload;
use modules\classifieds\apps\AdminPanel\IMenuItem;
use modules\import_listings\lib\ImportListingsConfigRequestData;
use modules\import_listings\lib\ImportListingsFactory;

class ImportListingsHandler extends ContentHandlerBase implements IMenuItem
{
	protected $moduleName = 'import_listings';
	protected $functionName = 'import_listings';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->display('import_listings.tpl');

		if (\App()->Request['action'] == 'import')
		{
			$importListingsConfig = new ImportListingsConfigRequestData();
			if ($this->isValid($importListingsConfig))
			{
				try
				{
                    $uploader = new Upload(\App()->UploadedFiles['import_file']['tmp_name'], \App()->UploadedFiles['import_file']['name']);
                    $file = $uploader->move(PATH_TO_ROOT . \App()->SystemSettings['TempFilesDir'], \App()->UploadedFiles['import_file']['name']);


                    $importListingsConfig->setFilePath($file->getPathname());
					$factory = new ImportListingsFactory();
					$transceiver = $factory->createDataTransceiver($importListingsConfig, []);
					$transceiver->perform();
					$transceiver->finalize();
                    $afterImportListingAction = new ExtensionPoint('modules\import_listings\apps\IAfterImportListingsAction');
                    foreach($afterImportListingAction as $action)
                    {
                        $action->setImportConfig($importListingsConfig);
                        $action->perform();
                    }
					$template_processor->assign('log', $transceiver->getLog());
					$template_processor->display('import_listings_log.tpl');
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
		}

		$template_processor->assign('freePackageFeatures', \App()->PackageManager->getFreePackageFeatures());
		$template_processor->assign('packages', \App()->PackageManager->getPackagesByClass('ListingPackage'));
		$template_processor->assign('categories', \App()->CategoryManager->getAllCategoriesInfo());
		$template_processor->display('import_listings_form.tpl');
	}

	private function isValid($config)
	{
		$isValid = true;

		$activationDate = $config->getActivationDate();
		if (!empty($activationDate) && !\App()->I18N->isValidDate($activationDate))
		{
			\App()->ErrorMessages->addMessage('INVALID_DATE');
			$isValid = false;
		}

		return $isValid;
	}

	public static function getOrder()
	{
		return 500;
	}

	public function getCaption()
	{
		return "Import Listings";
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

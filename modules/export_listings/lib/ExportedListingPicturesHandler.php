<?php
/**
 *
 *    Module: export_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19779, 2016-06-17 13:19:16
 *
 *    This file is part of the 'export_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_listings\lib;

class ExportedListingPicturesHandler
{
    const EXPORT_PICTURE_STYLE = 'large';
	private $exportFilesDirectory;
	private $picturesDirectory;
	private $listingGallery;

	public function setExportFilesDirectory($exportFilesDirectory)
	{
		$this->exportFilesDirectory = $exportFilesDirectory;
	}
	public function setListingGallery($listingGallery)
	{
		$this->listingGallery = $listingGallery;
	}
	public function setPicturesDirectory($picturesDirectory)
	{
		$this->picturesDirectory = $picturesDirectory;
	}
	public function handle($exportedListing)
	{
		$data = $exportedListing->getData();
		if (isset($data['pictures']['collection']))
		{
			$picturesPath = [];
            $fileSystem = \App()->FileSystem;

            if (!is_dir($this->exportFilesDirectory . $this->picturesDirectory))
            {
                mkdir($this->exportFilesDirectory . $this->picturesDirectory, 0777, true);
            }

			$siteUrl  = \App()->SystemSettings['SiteUrl'];
			foreach ($data['pictures']['collection'] as $pictureInfo)
			{
				if (!isset($pictureInfo['file'])) continue;

                $picture = isset($pictureInfo['file'][self::EXPORT_PICTURE_STYLE])
                    ? $pictureInfo['file'][self::EXPORT_PICTURE_STYLE]
                    : $pictureInfo['file'];

				$picFile = str_replace($siteUrl .  '/', '', $picture['url']);
				if (file_exists($picFile))
                {
                    $pic = $this->picturesDirectory . $data['id'] . '_' . $pictureInfo['id'] . '_' . $picture['name'];
                    $fileSystem->copyFile($picFile, $this->exportFilesDirectory . $pic);
                    $picturesPath[] = $pic;
                }
			}
			$data['pictures'] = join(";", $picturesPath);
			$exportedListing->setData($data);
		}
	}
}

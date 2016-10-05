<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM\Types\Uploader;


use Codesleeve\Stapler\File\FileInterface;
use Codesleeve\Stapler\File\Image\Resizer as StaplerResizer;
use Codesleeve\Stapler\Style;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use stojg\crop\CropBalanced;

class Resizer extends StaplerResizer
{
    /**
     * Resize an image using the computed settings.
     *
     * @param FileInterface $file
     * @param Style $style
     *
     * @return string
     */
    public function resize(FileInterface $file, Style $style)
    {
        $filePath = $this->randomFilePath($file->getFilename());
        list($width, $height, $option) = $this->parseStyleDimensions($style);

        if (isset($style->convertOptions['CropBalanced']) && $style->convertOptions['CropBalanced']) {
            $option = 'balanced';
        }

        $method = 'resize' . ucfirst($option);
        if ($method == 'resizeCustom') {
            $this->resizeCustom($file, $style->dimensions)
                ->save($filePath, $style->convertOptions);

            return $filePath;
        }

        /**
         * @var $image  ImageInterface
         */
        $image = $this->imagine->open($file->getRealPath());

        if ($style->autoOrient) {
            $image = $this->autoOrient($file->getRealPath(), $image);
        }
        $image = $this->$method($image, $width, $height);

        if (isset($style->convertOptions['watermark']) && $style->convertOptions['watermark']) {
            $image = $this->watermark($image);
        }

        $image->save($filePath, $style->convertOptions);

        return $filePath;
    }

    protected function resizeBalanced(ImageInterface $image, $width, $height)
    {
        $croper = new CropBalanced();
        $croper->setImage($image->getImagick());
        $croper->resizeAndCrop($width, $height);
        return $image;
    }

    protected function watermark(ImageInterface $image)
    {
        $position = \App()->SettingsFromDB->getSettingByName('watermark_position');
        $watermarkImagePath = $this->getWaterMarkImagePath();
        $transparency = (int)\App()->SettingsFromDB->getSettingByName('watermark_transparency');
        $watermark = $this->imagine->open($watermarkImagePath);
        $watermarkImage = $watermark->getImagick();

        if (!$watermarkImage->getImageAlphaChannel())
        {
            $watermarkImage->setImageAlphaChannel(\Imagick::ALPHACHANNEL_SET);
        }

        if ($watermarkImage->getImageAlphaChannel() !== \Imagick::ALPHACHANNEL_ACTIVATE)
        {
            $watermarkImage->setImageAlphaChannel(\Imagick::ALPHACHANNEL_OPAQUE);
        }

        if ($transparency < 100)
        {
            // NOTE: Using setImageOpacity will destroy current alpha channels!
            $watermarkImage->evaluateImage(\Imagick::EVALUATE_MULTIPLY, $transparency / 100, \Imagick::CHANNEL_ALPHA);
        }

        $watermarkSize = $watermark->getSize();
        $size = $image->getSize();

        list($imagePositionY, $imagePositionX) = explode('-', $position);
        switch ($imagePositionX) {
            case 'center':
                $positionX = ($size->getWidth() - $watermarkSize->getWidth()) / 2;
                break;
            case 'right':
                $positionX = $size->getWidth() - $watermarkSize->getWidth();
                break;
            default:
                $positionX = 0;
        }
        switch ($imagePositionY) {
            case 'middle':
                $positionY = ($size->getHeight() - $watermarkSize->getHeight()) / 2;
                break;
            case 'bottom':
                $positionY = $size->getHeight() - $watermarkSize->getHeight();
                break;
            default:
                $positionY = 0;
        }

        $point = new Point($positionX, $positionY);

        $image->paste($watermark, $point);

        return $image;
    }

    protected function getWaterMarkImagePath()
    {
        $watermarkImagePath = PATH_TO_ROOT . \App()->SystemSettings['PicturesDir'] . \App()->SettingsFromDB->getSettingByName('watermark_picture');
        if (is_null($watermarkImagePath)) {
            throw new PictureUploadException('WATERMARK_IMAGE_PATH_NOT_SET');
        }
        if (!is_file($watermarkImagePath)) {
            throw new PictureUploadException('WRONG_WATERMARK_IMAGE_PATH_SPECIFIED');
        }

        $image_path_info = pathinfo($watermarkImagePath);
        if (!in_array(strtoupper($image_path_info['extension']), array('GIF', 'JPG', 'JPEG', 'PNG'))) {
            throw new PictureUploadException('NOT_SUPPORTED_IMAGE_FORMAT');
        }
        return $watermarkImagePath;
    }

}

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


trait PropertyInfo
{
    protected function normalizePropertyInfo($info)
    {
        $settings = \App()->SystemSettings;
        $dir = $this->object ? $this->object->getTableName() : (isset($info['table_alias']) ? $info['table_alias'] : 'common');
        $info['base_path'] = PATH_TO_ROOT . $settings['PicturesDir'];

        if (!isset($info['path'])) {
            $info['path'] = ':app_root/' . $dir . '/:attachment/:id/:style/:filename';
        }

        if (!isset($info['url'])) {
            $info['url'] = $settings['SiteUrl'] . '/' . PATH_TO_ROOT . $settings['PicturesDir'] . $dir . '/:attachment/:id/:style/:filename';
        }

        if (!isset($info['styles'])) {
            $info['styles'] = [
                $info['id'] => $info['width'] . 'x' . $info['height']
            ];
        }

        return $info;
    }

    function watermark(&$image)
    {
        $watermarker = $this->WatermarkerFactory->getImageWatermarker();
        if ($watermarker->canPerform())
        {
            $watermarker->perform($image);
        }
    }
}

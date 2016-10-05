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

use Codesleeve\Stapler\AttachmentConfig;
use Codesleeve\Stapler\Factories\Attachment;

class AttachmentFactory extends Attachment
{

    /**
     * Build out the dependencies required to create
     * a new attachment object.
     *
     * @param string $name
     * @param array  $options
     *
     * @return array
     */
    protected static function buildDependencies($name, array $options)
    {
        return [
            new AttachmentConfig($name, $options),
            new Interpolator(),
            Stapler::getResizerInstance('Imagine\Imagick\Imagine'),
        ];
    }

}

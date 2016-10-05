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


use Codesleeve\Stapler\Attachment;

class Interpolator extends \Codesleeve\Stapler\Interpolator
{
    /**
     * Returns a sorted list of all interpolations.  This list is currently hard coded
     * (unlike its paperclip counterpart) but can be changed in the future so that
     * all interpolation methods are broken off into their own class and returned automatically.
     *
     * @return array
     */
    protected function interpolations()
    {
        $interpolations = parent::interpolations();
        $interpolations[':parent_id'] = 'parentId';
        return $interpolations;
    }

    /**
     * Returns the parentId of the current object instance.
     *
     * @param Attachment $attachment
     * @param string     $styleName
     *
     * @return string
     */
    protected function parentId(Attachment $attachment, $styleName = '')
    {
        return $this->ensurePrintable($attachment->getInstance()->getParentKey());
    }


}

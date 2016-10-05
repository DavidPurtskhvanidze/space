<?php
/**
 *
 *    Module: sass v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: sass-7.5.0-1
 *    Tag: tags/7.5.0-1@19833, 2016-06-17 13:21:49
 *
 *    This file is part of the 'sass' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\sass\lib;


use modules\template_manager\lib\AbstractDesignManager;

class DesignSassManager extends AbstractDesignManager
{
    public $designFileName = 'design.scss';

    public function saveDesign($designContent)
    {
        parent::saveDesign($designContent);
        $processor = new SassProcessor();
        $processor->setTheme($this->theme)->setModuleName($this->moduleName)->getFile();
    }
}

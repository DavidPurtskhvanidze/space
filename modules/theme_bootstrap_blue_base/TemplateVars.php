<?php
/**
 *
 *    Module: theme_bootstrap_blue_base v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: theme_bootstrap_blue_base-7.5.0-1
 *    Tag: tags/7.5.0-1@19842, 2016-06-17 13:22:19
 *
 *    This file is part of the 'theme_bootstrap_blue_base' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\theme_bootstrap_blue_base;


use modules\smarty_based_template_processor\lib\ITemplateVariable;

class TemplateVars implements ITemplateVariable
{

    public $inGlobalArray = true;

    public function getKey()
    {
        return 'PicturesDir';
    }

    public function getValue()
    {
        return \App()->SystemSettings['PicturesDir'];
    }
}

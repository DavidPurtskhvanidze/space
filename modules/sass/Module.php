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


namespace modules\sass;

class Module extends \core\Module
{
    protected $name = 'sass';
    protected $caption = 'Sass';
    protected $version = '7.5.0-1';
    protected $dependencies =
        [
            'smarty_based_template_processor',
        ];
}

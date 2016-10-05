<?php
/**
 *
 *    Module: template_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: template_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19839, 2016-06-17 13:22:09
 *
 *    This file is part of the 'template_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\template_manager\apps\SubDomain;
use modules\main\apps\SubDomain\IHtmlHeaderTagContentDisplayer;
use modules\template_manager\lib\ColorizeManager;

class ColorizeStyleInclude implements IHtmlHeaderTagContentDisplayer
{
	public function display()
	{
        $file = (new ColorizeManager())
            ->setFileSystem(\App()->FileSystem)
            ->setThemeName(\App()->getTemplateProcessor()->getTheme()->getName())
            ->getFile();

        if (\App()->FileSystem->file_exists($file))
        {
            echo '<link type="text/css" rel="stylesheet" href="' . \App()->SystemSettings->get('SiteUrl') . '/' . $file . '" media="screen">';
        }
	}
} 

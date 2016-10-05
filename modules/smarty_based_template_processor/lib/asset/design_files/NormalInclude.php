<?php
/**
 *
 *    Module: smarty_based_template_processor v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: smarty_based_template_processor-7.5.0-1
 *    Tag: tags/7.5.0-1@19835, 2016-06-17 13:21:56
 *
 *    This file is part of the 'smarty_based_template_processor' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\smarty_based_template_processor\lib\asset\design_files;

class NormalInclude extends AbstractInclude
{
    public function getHtmlCode($params, $templateProcessor)
    {
        $themeInheritanceBranch = $templateProcessor->getThemeInheritanceBranch();
        $modulesList = $this->modulesList;
        $cssFilesList = [];
        $siteUrl = \App()->SystemSettings['SiteUrl'];
        $processors = $this->processors;
        foreach ($modulesList as &$moduleName)
        {
            foreach ($themeInheritanceBranch as $theme)
            {
                foreach($processors as $processor)
                {
                    /**
                     * @var IProcessor $processor
                     */
                    $cssFile = $processor->setModuleName($moduleName)->setTheme($theme)->getFile();
                    if (!is_null($cssFile))
                    {
                        $cssFilesList[] =  $siteUrl . "/" . $cssFile;
                    }
                }
            }
        }
        return join("\n", array_map([$this, "getIncludeCssCode"], $cssFilesList));
    }
}

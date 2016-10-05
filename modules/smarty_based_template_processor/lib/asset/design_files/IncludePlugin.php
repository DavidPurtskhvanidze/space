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

use core\ExtensionPoint;
use modules\smarty_based_template_processor\lib\IPlugin;

class IncludePlugin implements IPlugin
{

    public function getPluginType()
    {
        return "function";
    }

    public function getPluginTag()
    {
        return "includeDesignFiles";
    }

    public function getPluginCallback()
    {
        $include = \App()->getAppMode() == 'production'
            ? new CachedInclude()
            : new NormalInclude();

        $modulesList = \App()->ModuleManager->getModuleNamesByStatus('ENABLED');
        if(($key = array_search(\App()->SystemSettings['MainModuleName'], $modulesList)) !== false) {
            unset($modulesList[$key]);
        }
        $modulesList[]=\App()->SystemSettings['MainModuleName'];

        $processors = new ExtensionPoint('modules\smarty_based_template_processor\lib\asset\design_files\IProcessor');
        $include
            ->setProcessors($processors)
            ->setModulesList($modulesList);

        return [$include, 'getHtmlCode'];
    }
}

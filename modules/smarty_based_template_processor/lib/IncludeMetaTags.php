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


namespace modules\smarty_based_template_processor\lib;

class IncludeMetaTags implements IPlugin
{
    public function getPluginType()
    {
        return "function";
    }

    public function getPluginTag()
    {
        return "meta";
    }

    public function getPluginCallback()
    {
        return array($this, "appendMetaTags");
    }

    function appendMetaTags($params)
    {
        $appendedMetaTags = \App()->GlobalTemplateVariable->getMetaTags();
        $metaTags = $appendedMetaTags . "\n" . $this->getMetaTagCode($params);
        \App()->GlobalTemplateVariable->setMetaTags($metaTags);
    }


    private function getMetaTagCode($params)
    {
        return "<meta property=\"{$params['tagProperty']}\" content=\"{$params['tagContent']}\">";
    }
}

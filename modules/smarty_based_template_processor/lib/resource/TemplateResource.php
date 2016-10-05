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


namespace modules\smarty_based_template_processor\lib\resource;


use Smarty_Internal_Template;
use Smarty_Template_Source;

class TemplateResource extends \Smarty_Resource_Custom
{
    const DEFAULT_RESOURCE_TYPE = 'template_resource';
    const MODULE_NAME_SEPARATOR = '^';

    private $templateProcessor;
    private $moduleName;
    private $theme;

    public function __construct(&$templateProcessor)
    {
        $this->templateProcessor = $templateProcessor;
        $this->moduleName = $templateProcessor->getModuleName();
        $this->theme = $templateProcessor->getTheme();
    }

    public function getTemplate($templateName)
    {
        @list($templateName, $moduleName) = array_reverse(explode(self::MODULE_NAME_SEPARATOR, $templateName, 2));
        if (empty($moduleName)) $moduleName = $this->moduleName;
        return $this->theme->getTemplate($moduleName, $templateName);
    }

    /**
     * fetch template and its modification time from data source
     *
     * @param string $name template name
     * @param string &$source template source
     * @param integer &$mtime template modification timestamp (epoch)
     */
    protected function fetch($name, &$source, &$mtime)
    {
        $source = ($this->templateProcessor->ifAddTemplateStartEndComments()) ? $this->getTemplate($name)->getContent() : $this->getTemplate($name)->getContentWithoutComments();
        $mtime = $this->fetchTimestamp($name);
    }

    protected function fetchTimestamp($name)
    {
        return $this->getTemplate($name)->getLastModifiedTime();
    }

    /**
     * populate Source Object with meta data from Resource
     *
     * @param Smarty_Template_Source   $source    source object
     * @param Smarty_Internal_Template $_template template object
     */
    public function populate(Smarty_Template_Source $source, Smarty_Internal_Template $_template=null)
    {
        $source->filepath = $source->type . ':' . $source->name;
        $source->uid = sha1($source->type . ':' . $source->name);

        $mtime = $this->fetchTimestamp($source->name);
        if ($mtime !== null) {
            $source->timestamp = $mtime;
        } else {
            $this->fetch($source->name, $content, $timestamp);
            $source->timestamp = isset($timestamp) ? $timestamp : false;
            if( isset($content) )
                $source->content = $content;
        }
        $source->exists = !!$source->timestamp;
    }
}

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


use apps\ITheme;

abstract class AbstractProcessor implements IProcessor
{
    /**
     * @var ITheme
     */
    protected $theme;
    /**
     * @var string
     */
    protected $moduleName;
    /**
     * @param ITheme $theme
     * @return $this
     */
    public function setTheme(ITheme $theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @param string $moduleName
     * @return $this
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
        return $this;
    }
}

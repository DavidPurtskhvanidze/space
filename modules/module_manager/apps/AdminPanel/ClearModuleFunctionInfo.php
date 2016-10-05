<?php
/**
 *
 *    Module: module_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: module_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19801, 2016-06-17 13:20:13
 *
 *    This file is part of the 'module_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\module_manager\apps\AdminPanel;

class ClearModuleFunctionInfo implements IAfterModulesEnable, IAfterModulesInstall, IAfterModulesUpgrade
{
    public function perform($modules)
    {
        \App()->selfHttpRequest(['SYSCOMMAND' => 'CLEAR_MODULE_FUNCTION_INFO_CACHE']);
    }
}

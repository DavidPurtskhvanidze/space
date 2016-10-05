<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\AdminPanel\scripts;

class ClearCacheHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'clear_cache';

	public function respond()
	{
        $cacheType = \App()->Request->getValueOrDefault('cache_type', 'smarty');

        switch($cacheType)
        {
            case 'smarty':
                \App()->FileSystem->removeRecursively(PATH_TO_ROOT . \App()->SystemSettings['CacheDir'] . 'templates/', false);
                $massage = 'SYSTEM_CACHE_CLEARED';
                break;
            case 'blocks':
                \App()->CacheManager->clearCacheByType('module_function_content');
                $massage = 'BLOCKS_CACHE_CLEARED';
                break;
        }

		if (isset($_REQUEST['returnBackUri']))
		{
			\App()->SuccessMessages->addMessage($massage);
			throw new \lib\Http\RedirectException($_REQUEST['returnBackUri']);
		}
	}
}
?>

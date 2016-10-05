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


use MatthiasMullie\Minify\CSS;

class CachedInclude extends AbstractInclude
{

    public function getHtmlCode($params, $templateProcessor)
    {
        $themeInheritanceBranch = $templateProcessor->getThemeInheritanceBranch();
        $modulesList = $this->modulesList;

        $cssFilesList = [];
        $processors = $this->processors;
        foreach ($modulesList as $moduleName)
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
                        $cssFilesList['real'][] =  $cssFile;
                        $cssFilesList['mod'][] =  $cssFile . filemtime($cssFile);
                    }
                }
            }
        }

        $assetCacheDir = \App()->FileSystem->getWritableCacheDir('asset');
        $cachedFileName = md5(implode('', $cssFilesList['mod'])) . '.css';
        $baseUrl = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl')  . '/';
        $cachedFileUrl = $baseUrl . \App()->SystemSettings['CacheDir'] . '/asset/' . $cachedFileName;
        if (!file_exists($assetCacheDir . '/' .$cachedFileName))
        {
            $minifier = new CSS();
            foreach ($cssFilesList['real'] as $file)
            {
                $path = str_replace('../', '', $baseUrl . dirname($file) . '/');
                $fileContent = @file_get_contents($file);

                $fileContent = preg_replace('/\(\s+/', '(', $fileContent);
                $fileContent = preg_replace('/\s+\)/', ')', $fileContent);
                $fileContent = str_replace(['("', '(\''], '(', $fileContent);
                $fileContent = str_replace(['")', '\')'], ')', $fileContent);

                $fileContent = preg_replace('/(?:\.\.\/)+(.*?\))/', $path . '$1', $fileContent);
                $fileContent = preg_replace('/(?:\.\.\/)+(.*?\))/i', $path . '$1', $fileContent);
                $fileContent = preg_replace('/(url\((?!.*\/\/))/i', '$1' . $path, $fileContent);

                $minifier->add($fileContent);
            }
            \App()->FileSystem->file_put_contents($assetCacheDir . '/' .$cachedFileName, $minifier->minify());
        }

        return join("\n", array_map(array($this, "getIncludeCssCode"), [$cachedFileUrl]));
    }
}

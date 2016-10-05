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

namespace modules\sass\lib;

use core\FileSystem;
use Leafo\ScssPhp\Compiler;
use modules\smarty_based_template_processor\lib\asset\design_files\AbstractProcessor;

class SassProcessor extends AbstractProcessor
{
    const DESIGN_SASS_FILENAME = "design.scss";
    /**
     * @var FileSystem
     */
    private $fileSystem;

    public function __construct()
    {
        $this->fileSystem = \App()->FileSystem;
    }

    /**
     * @return string | null
     */
    public function getFile()
    {
        $cacheDir = PATH_TO_ROOT . \App()->SystemSettings['CacheDir'] . '/' . 'styles/' . $this->theme->getName() . '/' . $this->moduleName . '/';

        $file = null;

        if (file_exists($sassFile = $this->theme->getFilePath($this->moduleName, self::DESIGN_SASS_FILENAME)))
        {
            $cssFile = md5($sassFile . filemtime($sassFile)) . '.css';
            if (file_exists($cacheDir . $cssFile))
            {
                return $cacheDir . $cssFile;
            }

            $scss = new Compiler();
            $file = $cacheDir . '/' . $cssFile;
            $this->fileSystem->getWritableDir($cacheDir);
            $this->fileSystem->copyDirContents($this->theme->getPathToFilesDir($this->moduleName), $cacheDir);
            $this->fileSystem->deleteFile($cacheDir . self::DESIGN_SASS_FILENAME);
            $this->fileSystem->file_put_contents($file, $scss->compile($this->fileSystem->getContentsOfFile($sassFile)));
        }
        return $file;
    }

}

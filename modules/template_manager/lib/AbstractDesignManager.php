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

namespace modules\template_manager\lib;


use apps\Theme;

abstract class AbstractDesignManager
{
    protected $appID;


    /**
     * @var Theme
     */
    protected $theme;

    protected $moduleName;
    protected $pathToDesignFile;

    protected $designFileName = 'design.css';

    /**
     * @param $appID String Application ID
     * @param $theme Theme Object
     */
    public function __construct($appID, $theme)
    {
        $this->moduleName = \App()->SystemSettings['PageTemplatesModuleName'];
        $this->appID = $appID;
        $this->theme = $theme;
        $this->pathToDesignFile = $this->theme->getFilePath($this->moduleName, $this->designFileName);
    }

    /**
     * @return string
     */
    public function getDesignFileName()
    {
        return $this->designFileName;
    }

    public function getDesignContent()
    {
        $this->createDesignFileIfNotExists();
        if (\App()->FileSystem->file_exists($this->pathToDesignFile))
        {
            return \App()->FileSystem->file_get_contents($this->pathToDesignFile);
        }
        return null;
    }

    public function saveDesign($designContent)
    {
        $this->createDesignFileIfNotExists();
        if ($this->doesDesignFileExist())
        {
            \App()->FileSystem->file_put_contents($this->pathToDesignFile, $designContent);
            return true;
        }
        return false;
    }

    public function createDesignFileIfNotExists()
    {
        $error = $this->getError();
        if (!\App()->FileSystem->file_exists($this->pathToDesignFile) and empty($error))
        {
            \App()->FileSystem->getWritableDir($this->theme->getPathToFilesDir($this->moduleName));
            \App()->FileSystem->createFile($this->pathToDesignFile);
        }
    }

    public function getError()
    {
        $error = array();
        if ($this->doesDesignFileExist()) //Е�?ли файл $theme_name/main/_files/design. �?уще�?твует
        {
            if (!$this->isDesignFileWritable()) //Е�?ли файл $theme_name/main/_files/design. З�?КРЫТ дл�? запи�?и
            {
                $error = array('type' => 'FILE_IS_NOT_WRITABLE', 'details' => array('filename' => 'design.'));
            }
            return $error;
        }
        if ($this->doesFilesDirectoryExist()) //Е�?ли директори�? $theme_name/main/_files/ �?уще�?твует
        {
            if (!$this->isFilesDirectoryWritable()) //Е�?ли директори�? $theme_name/main/_files/ З�?КРЫТ�? дл�? запи�?и
            {
                $error = array('type' => 'DIRECTORY_IS_NOT_WRITABLE', 'details' => array('directory' => $this->theme->getPathToFilesDir($this->moduleName)));
            }
            return $error;
        }
        if ($this->doesMainModuleDirectoryExist()) //Е�?ли директори�? $theme_name/main/ �?уще�?твует
        {
            if (!$this->isMainModuleDirWritable()) //Е�?ли директори�? $theme_name/main/ З�?КРЫТ�? дл�? запи�?и
            {
                $error = array('type' => 'DIRECTORY_IS_NOT_WRITABLE', 'details' => array('directory' => $this->theme->getPathToThemeDir() . $this->moduleName . DIRECTORY_SEPARATOR));
            }
            return $error;
        }
        if (!$this->isThemeDirWritable()) //Е�?ли директори�? $theme_name/ З�?КРЫТ�? дл�? запи�?и
        {
            $error = array('type' => 'DIRECTORY_IS_NOT_WRITABLE', 'details' => array('directory' => $this->theme->getPathToThemeDir()));
        }
        return $error;
    }

    protected function doesDesignFileExist()
    {
        return \App()->FileSystem->file_exists($this->pathToDesignFile);
    }

    protected function doesFilesDirectoryExist()
    {
        return \App()->FileSystem->file_exists($this->theme->getPathToFilesDir($this->moduleName));
    }

    protected function doesMainModuleDirectoryExist()
    {
        return \App()->FileSystem->file_exists($this->theme->getPathToThemeDir() . $this->moduleName . DIRECTORY_SEPARATOR);
    }

    protected function isDesignFileWritable()
    {
        return \App()->FileSystem->is_writable($this->pathToDesignFile);
    }

    protected function isFilesDirectoryWritable()
    {
        return \App()->FileSystem->is_writable($this->theme->getPathToFilesDir($this->moduleName));
    }

    protected function isMainModuleDirWritable()
    {
        return \App()->FileSystem->is_writable($this->theme->getPathToThemeDir() . $this->moduleName . DIRECTORY_SEPARATOR);
    }

    protected function isThemeDirWritable()
    {
        return \App()->FileSystem->is_writable($this->theme->getPathToThemeDir());
    }
}

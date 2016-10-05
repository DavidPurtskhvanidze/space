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

use core\FileSystem;

class ColorizeManager
{
    private $themeName;
    /**
     * @var FileSystem
     */
    private $fileSystem;

    private $cacheDirName = 'asset';

    protected $rules = [

	    'colorize-main' => [
		   'rules' => [
			   'background' => ['Caption' => 'Background Color'],
			   'color' => ['Caption' => 'Text Color']
           ],
		   'selector' => '.colorize-main',
		   'Caption' => 'Main Colors',
        ],

	    'colorize-menu' => [
		    'rules' => [
			    'border-color' => ['Caption' => 'Divider color'],
			    'background' => ['Caption' => 'Background Color'],
			    'color' => ['Caption' => 'Color of text in the menu']
            ],
		    'selector' => 'body .colorize-menu',
		    'Caption' => 'Menu Colors'
        ],

	    'colorize-left-menu' => [
		    'rules' => [
			    'background' => ['Caption' => 'Background Color'],
			    'color' => ['Caption' => 'Text Color'],
            ],
		    'selector' => '.colorize-left-menu, .colorize-left-menu a, .colorize-left-menu li:hover',
		    'Caption' => 'Left Menu Colors'
        ],


	    'colorize-footer' => [
		    'rules' => [
			    'background' => ['Caption' => 'Background Color'],
			    'color' => ['Caption' => 'Text Color'],
            ],
		    'selector' => '.colorize-footer',
		    'Caption' => 'Footer color'
        ],

	    'colorize-buttons-color' => [
		    'rules' => [
			    'background' => ['Caption' => 'Background Color'],
			    'color' => ['Caption' => 'Text Color'],
			    'border-color' => ['Caption' => 'Border color']
            ],
		    'selector' => 'button, input[type="submit"], input[type="reset"], input[type="button"]',
		    'Caption' => 'Buttons Color',
		    'className' => false,
        ],

	    'colorize-links-color' => [
		    'rules' => [
			    'color' => ['Caption' => 'Links Color']
            ],
		    'selector' => 'a',
		    'Caption' => 'Links Color',
		    'className' => false,
        ],
    ];

    public function addRule($rule)
    {
        $key = key($rule);
        $this->rules[$key] = $rule[$key];
    }

	private function defineValues()
	{
		$colors = json_decode($this->getColors(), true);
		if (empty($colors)) return false;
		foreach($colors as $colorKey => $rules)
		{
			foreach($rules as $ruleKey => $ruleValue)
			{
				$this->rules[$colorKey]['rules'][$ruleKey]['value'] = $ruleValue;
			}
		}
	}

	public function getRules()
	{
		$this->defineValues();
		return $this->rules;
	}

	private function getColors()
	{
		$row = \App()->DB->getSingleRow("SELECT * FROM `template_manager_colorize` WHERE `theme` = '{$this->themeName}'");
		return $row['styles'];
	}

	public function save($colorize)
	{
		$colorize = json_encode($colorize);

		\App()->DB->query("INSERT INTO `template_manager_colorize` (`theme`, `styles`, `lastModified`) VALUES (?s, ?s, NOW())
						   ON DUPLICATE KEY UPDATE `styles` = ?s, `lastModified` = NOW()",
						   $this->themeName, $colorize, $colorize);

        return $this->updateFile();
	}

	public function getLastModified()
	{
		$row = \App()->DB->getSingleRow("SELECT * FROM `template_manager_colorize` WHERE `theme` = '{$this->themeName}'");
		return $row['lastModified'];
	}

	public function generateCss()
	{
		$colors = $this->getRules();
		$css = '';
		array_walk($colors, function ($v, $k) use (&$css) {
			$css .= $v['selector'] . '{';
			foreach ($v['rules'] as $ruleKey => $ruleValue)
			{
				if ( !empty($ruleValue['value']))
				{
					$css .= $ruleKey . ': ' . $ruleValue['value'] . ' !important;';
				}
			}

			$css .= '}';
		});
		return $css;
	}

    public function getFile()
    {
        return PATH_TO_ROOT . \App()->SystemSettings['CacheDir'] . '/' . $this->cacheDirName . '/' . $this->themeName . '.css';
    }

    protected function updateFile()
    {
        $fileName = $this->fileSystem->getWritableCacheDir($this->cacheDirName) . '/' . $this->themeName . '.css';
        $this->fileSystem->putContentsToFile($fileName, $this->generateCss());
        return $fileName;
    }

	public function getColorizeClasses()
	{
		return $this->rules;
	}

    /**
     * @param string $themeName
     * @return ColorizeManager
     */
    public function setThemeName($themeName)
    {
        $this->themeName = $themeName;

        return $this;
    }

    /**
     * @param FileSystem $fileSystem
     * @return ColorizeManager
     */
    public function setFileSystem(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;

        return $this;
    }

    /**
     * @param string $cacheDirName
     * @return ColorizeManager
     */
    public function setCacheDirName($cacheDirName)
    {
        $this->cacheDirName = $cacheDirName;
        return $this;
    }

}

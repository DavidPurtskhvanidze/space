<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib;

class InterfaceImplementationIndexer
{

	private $missingClasses = array();
	private $interfaceImplementations = array();

	public function autoloadRegisterMissingClass($name)
	{
		if (strpos($name, '\\') !== false) throw new \Exception($name);
		eval("class $name{}");
		array_push($this->missingClasses,$name);
	}

	public function scanDirectory($path)
	{
		$currentDir = realpath($path);
		$Directory = new \RecursiveDirectoryIterator($currentDir);
		$Iterator = new \RecursiveIteratorIterator($Directory);
		$PhpFilesIterator = new \RegexIterator($Iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
		spl_autoload_register(array($this,'autoloadRegisterMissingClass'));
		$classesToExclude = array();
		$prevClasses = array();
		foreach($PhpFilesIterator as $name)
		{
			try
			{
				$prevClasses = get_declared_classes();
				@include_once($name[0]);
			}
			catch (\Exception $e)
			{
				$classesToExclude += array_diff(get_declared_classes(), $prevClasses);
			}
		}
		spl_autoload_unregister(array($this,'autoloadRegisterMissingClass'));
		$knownClasses = array_diff(get_declared_classes(), $classesToExclude);
		foreach($knownClasses as $className)
		{
			$class = new \ReflectionClass($className);
			if ($class->isAbstract()) continue;
			foreach($class->getInterfaceNames() as $iName)
			{
				if (!isset($this->interfaceImplementations[$iName]))
				{
					$this->interfaceImplementations[$iName] = array($className);
				}
				else
				{
					array_push($this->interfaceImplementations[$iName], $className);
				}
			}
		}
		$this->interfaceImplementations = array_map('array_unique', $this->interfaceImplementations);
		return $this->interfaceImplementations;
	}

	public function getMissingClasses()	{ return $this->missingClasses; }
	public function getInterfaceImplementations() { return $this->interfaceImplementations; }
	public function getInterfaceImplementationsAsPhpCode() { return var_export($this->interfaceImplementations,TRUE); }
}

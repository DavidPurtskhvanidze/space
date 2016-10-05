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

class Template
{
	private $id;
	private $pathToTemplate;

	public function setId($id)
	{
		$this->id = $id;
	}
	public function getId()
	{
		return $this->id;
	}
	public function setPathToTemplate($pathToTemplate)
	{
		$this->pathToTemplate = $pathToTemplate;
	}
	public function getLastModifiedTime()
	{
		return \App()->FileSystem->filemtime($this->pathToTemplate);
	}
	public function getContentWithoutComments()
	{
		return \App()->FileSystem->file_get_contents($this->pathToTemplate);
	}
	public function getContent()
	{
		$content = \App()->FileSystem->file_get_contents($this->pathToTemplate);
		if (strpos($content, "<!DOCTYPE") !== false)
		{
			// as comment before <!DOCTYPE is not allowed we insert it before opening html tag
			return substr_replace($content, $this->getHeaderComment(), strpos($content, "<html"), 0) . $this->getFooterComment();
		}
		elseif (strpos($content, "<?xml") !== false)
		{
			// as comment before <?xml is not allowed we insert it after the first occurrence of the '>' symbol
			return substr_replace($content, $this->getHeaderComment(), strpos($content, ">") + 1, 0) . $this->getFooterComment();
		}
		elseif (strpos($content, "<html") !== false)
		{
			// as comment before <html is not allowed we insert it after the first occurrence of the '>' symbol
			return substr_replace($content, $this->getHeaderComment(), strpos($content, ">") + 1, 0) . $this->getFooterComment();
		}
		return $this->getHeaderComment() . $content . $this->getFooterComment();
	}
	
	private function getHeaderComment()
	{
		return "<!-- START " . $this->id . " -->\r\n";
	}
	private function getFooterComment()
	{
		return "\r\n<!-- END " . $this->id . " -->\r\n";
	}
}

?>

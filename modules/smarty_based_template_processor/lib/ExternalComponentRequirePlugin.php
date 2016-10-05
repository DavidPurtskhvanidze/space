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

class ExternalComponentRequirePlugin implements IPlugin, IFilter
{
	const EXTERNAL_COMPONENTS_PLACEHOLDER = '<!-- #EXTERNAL_COMPONENTS_PLACEHOLDER# -->';

	public function getFilterType()
	{
		return "output";
	}

	public function getFilterCallback()
	{
		return array($this, 'replacePlaceholderWithExternalComponentsLinks');
	}

	public function getPluginType()
	{
		return "function";
	}

	public function getPluginTag()
	{
		return "require";
	}

	public function getPluginCallback()
	{
		return array($this, 'requireComponent');
	}

	public function requireComponent($params)
	{
		\App()->ExternalComponents->requireComponent($params['component'], $params['file'], (isset($params['type']) ? $params['type'] : null));
	}

	public function replacePlaceholderWithExternalComponentsLinks($templateSource)
	{
		if (strpos($templateSource, self::EXTERNAL_COMPONENTS_PLACEHOLDER) === false) return $templateSource;
		return str_replace(self::EXTERNAL_COMPONENTS_PLACEHOLDER, $this->getHtmlCode(), $templateSource);
	}

	private function getHtmlCode()
	{
		$htmlCode = "<!-- external components start -->\n";
		$componentExists = false;
		foreach (\App()->ExternalComponents->getRequiredComponents() as $component)
		{
			$componentUrl = $this->getComponentUrl($component['component'], $component['file'], $componentExists);
			if (!$componentExists)
			{
				$htmlCode .= "<!-- External component '{$component['component']}' - '{$component['file']}' not found -->\n";
			}
			else if ($component['type'] == 'js')
			{
				$htmlCode .= "<script type=\"text/javascript\" src=\"$componentUrl\"></script>\n";
			}
			else if ($component['type'] == 'css')
			{
				$htmlCode .= "<link rel=\"StyleSheet\" type=\"text/css\" href=\"$componentUrl\" />\n";
			}
		}
		$htmlCode .= "<!-- external components end -->";
		return $htmlCode;
	}

	private function getComponentUrl($componentId, $file, & $componentExists)
	{
        if ($componentId == 'absolute_url')
        {
            $componentExists = true;
            return $file;
        }
		$filePath = \App()->PathManager->getExternalComponentsPath() . $componentId . "/" . $file;
		if (!is_file($filePath))
		{
			$componentExists = false;
			return \App()->ObjectMother->createFileNotFoundAction()->execute($filePath);
		}
		$componentExists = true;
		return \App()->SystemSettings['SiteUrl'] . "/" . $filePath;
	}
}

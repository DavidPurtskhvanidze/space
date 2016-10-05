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

use modules\smarty_based_template_processor\lib\resource\TemplateResource;

class TemplateProvider
{
	private $templateProcessor;
    private $templateResource;

	public function setTemplateProcessor($templateProcessor)
	{
		$this->templateProcessor = $templateProcessor;
	}

	public function registerResources()
	{
        $this->templateResource = new TemplateResource($this->templateProcessor);
		$this->templateProcessor->default_resource_type = TemplateResource::DEFAULT_RESOURCE_TYPE;
		$this->templateProcessor->registerResource(TemplateResource::DEFAULT_RESOURCE_TYPE, $this->templateResource);
	}

	public function getTemplate($templateName)
	{
		return $this->templateResource->getTemplate($templateName);
	}
}

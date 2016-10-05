<?php
/**
 *
 *    Module: image_carousel v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: image_carousel-7.5.0-1
 *    Tag: tags/7.5.0-1@19785, 2016-06-17 13:19:31
 *
 *    This file is part of the 'image_carousel' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\image_carousel\apps\AdminPanel\scripts;

use apps\AdminPanel\ContentHandlerBase;
use lib\Forms\Form;
use lib\Http\RedirectException;

class AddCarouselImageHandler extends ContentHandlerBase
{
	protected $displayName = 'Add Carousel Image';
	protected $moduleName = 'image_carousel';
	protected $functionName = 'add_carousel_image';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$carouselImage = \App()->CarouselImageManager->createCarouselImage(\App()->Request->getRequest());
		$carouselImage->deleteProperty('sid');
        $form = new Form($carouselImage);

		if (\App()->Request['action'] == 'save' && $form->isDataValid())
		{
			\App()->CarouselImageManager->saveObject($carouselImage);
			throw new RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'manage_image_carousel'));
		}

		$form->registerTags($templateProcessor);
		$templateProcessor->assign("form_fields", $form->getFormFieldsInfo());
		$templateProcessor->display("add_carousel_image.tpl");
	}
}

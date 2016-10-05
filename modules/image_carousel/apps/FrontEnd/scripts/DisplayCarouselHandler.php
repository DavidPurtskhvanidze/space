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


namespace modules\image_carousel\apps\FrontEnd\scripts;

class DisplayCarouselHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Image Carousel';
	protected $moduleName = 'image_carousel';
	protected $functionName = 'display_carousel';
	protected $parameters = array('width', 'height');

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign("images", \App()->CarouselImageManager->getEnabledForTemplate());
		$templateProcessor->assign("width", \App()->Request->getValueOrDefault('width', \App()->SettingsFromDB->getSettingByName('image_carousel_width')));
		$templateProcessor->assign("height", \App()->Request->getValueOrDefault('height', \App()->SettingsFromDB->getSettingByName('image_carousel_height')));
		$templateProcessor->assign("transitionTime", \App()->SettingsFromDB->getSettingByName('image_carousel_transition_time'));
		$templateProcessor->assign("showArrows", \App()->SettingsFromDB->getSettingByName('image_carousel_show_arrows'));
		$templateProcessor->assign("showNumbers", \App()->SettingsFromDB->getSettingByName('image_carousel_show_numbers'));
		$templateProcessor->display('image_carousel.tpl');
	}
}

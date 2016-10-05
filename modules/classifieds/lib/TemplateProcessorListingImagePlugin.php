<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib;

class TemplateProcessorListingImagePlugin implements \modules\smarty_based_template_processor\lib\IPlugin
{

	private $baseUrl = '';

	public function __construct()
	{
		$this->baseUrl = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl');
	}

	public function getPluginType()
	{
		return 'function';
	}

	public function getPluginTag()
	{
		return 'listing_image';
	}

	public function getPluginCallback()
	{
		return array($this, 'getListingImageTag');
	}

	public function getListingImageTag($params)
	{
		$pictureInfo = isset($params['pictureInfo']) ? $params['pictureInfo'] : null;
        if (!is_null($pictureInfo))
		{
			$alt = isset($params['alt']) ? $params['alt'] : $pictureInfo['caption'];

			if ($pictureInfo['storage_method'] == 'url')
			{
                $picUrl = $pictureInfo['file']['url'];
				if (isset($params['type']) && $params['type'] == 'big')
				{
					$width = \App()->SettingsFromDB->getSettingByName('listing_big_picture_width');
					return "<img src=\"{$picUrl}\" alt=\"{$alt}\" width=\"{$width}\"  title='{$pictureInfo['caption']}'/>";
				}
				elseif (isset($params['thumbnail']) && $params['thumbnail'] == 1)
				{
					$width = \App()->SettingsFromDB->getSettingByName('listing_thumbnail_width');
					$height = \App()->SettingsFromDB->getSettingByName('listing_thumbnail_height');
					return "<img class=\"img-thumbnail\" src=\"{$picUrl}\" alt=\"{$alt}\" width=\"{$width}\" height=\"{$height}\" title='{$pictureInfo['caption']}'/>";
				}
				else
				{
					$width = \App()->SettingsFromDB->getSettingByName('listing_picture_width');
					return "<img src=\"{$picUrl}\" alt=\"{$alt}\" width=\"{$width}\" title='{$pictureInfo['caption']}'/>";
				}
			}
			else
			{
                if (isset($params['type']) && $params['type'] == 'big')
				{
					return "<img class='img-responsive' src=\"{$this->getPictureUrl($pictureInfo, 'large')}\" alt=\"{$alt}\" title='{$pictureInfo['caption']}'/>";
				}
				elseif (isset($params['thumbnail']) && $params['thumbnail'] == 1)
				{
					return "<img class=\"img-thumbnail img-responsive\" src=\"{$this->getPictureUrl($pictureInfo, 'thumbnail')}\" alt=\"{$alt}\" title='{$pictureInfo['caption']}'/>";
				}
				else
				{
                    return "<img src=\"{$this->getPictureUrl($pictureInfo, 'picture')}\" alt=\"{$alt}\" class=\"img-responsive\" title='{$pictureInfo['caption']}'/>";
				}
			}
		}
	}

	private function getPictureUrl($pictureInfo, $style)
	{
		return $pictureInfo['file'][$style]['url'];
	}
}

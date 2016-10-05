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


namespace modules\image_carousel\apps\AdminPanel;

/**
 * Delete image
 * 
 * Interface designed for validating delete image action in AdminPanel. If it returns false, image will not be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeleteCarouselImageValidator
{
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}

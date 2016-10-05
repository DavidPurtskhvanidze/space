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


namespace modules\image_carousel\lib\CarouselImage;

class CarouselImage extends \lib\ORM\Object
{
	public function __construct()
	{
		$this->db_table_name = 'image_carousel_images';
	}
}

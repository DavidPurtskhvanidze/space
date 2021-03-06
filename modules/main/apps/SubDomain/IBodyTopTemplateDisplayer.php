<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\main\apps\SubDomain;

/**
 * Body top template displayer interface
 * 
 * Interface designed for displaying templates right after the start of body tag (on the top inside the body tag).
 * 
 * @category ExtensionPiont
 */
interface IBodyTopTemplateDisplayer
{
	/**
	 * Method for displaying templates
	 */
	public function display();
}

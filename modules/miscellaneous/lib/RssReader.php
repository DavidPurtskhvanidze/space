<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

interface RssReaderInterface
{
	public function getItems($filename, $limit = null);
}

class RssReader implements RssReaderInterface
{
	protected $wrappedFunctions;

	public function getItems($filename, $limit = null)
	{
		$rssParsed = $this->wrappedFunctions->simplexml_load_file($filename);
		if (empty($rssParsed->channel))
		{
			throw new InvalidRSSException($filename);
		}
		$rssItemFields = array('title', 'link', 'guid', 'description', 'pubDate');
		$items = array();
		$i = 0;
		foreach ($rssParsed->channel->item as $item)
		{
			$tItem = array();
			foreach ($rssItemFields as $field)
			{
				$tItem[$field] = (string)$item->$field;
			}
			$items[] = $tItem;
			if (is_int($limit) && ++$i >= $limit) break;
		}
		return $items;
	}
	public function setWrappedFunctions($wrappedFunctions)
	{
		$this->wrappedFunctions = $wrappedFunctions;
	}
}

abstract class RssReaderDecorator implements RssReaderInterface
{
	protected $rssReader = null;
	public function __construct($rssReader)
	{
		$this->rssReader = $rssReader;
	}
	protected function getRssReader()
	{
		return $this->rssReader;
	}
	public function getItems($filename, $limit = null)
	{
		return $this->getRssReader()->getItems($filename, $limit);
	}
}

class RssReaderWithCache extends RssReaderDecorator
{
	public function getItems($filename, $limit = null)
	{
		$cacheId = (is_null($limit)) ? $filename : "{$filename}__LIMIT_{$limit}";
		$items = \App()->CacheManager->getData('rss', $cacheId);
		if (is_null($items))
		{
			$items = parent::getItems($filename, $limit);
			\App()->CacheManager->updateData('rss', $cacheId, $items);
		}
		return $items;
	}
}

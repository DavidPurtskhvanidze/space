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

class ArrayWrapperWithEscaping implements \ArrayAccess, \Iterator
{
	/**
	 * Array
	 * @var array
	 */
	private $array;
	/**
	 * Current index
	 * @var int
	 */
	private $currentIndex;

	function __construct(&$array)
	{
		$this->array = & $array;
	}

	public function offsetExists($offset)
	{
		return isset($this->array[$offset]);
	}

	public function offsetGet($offset)
	{
		if (!isset($this->array[$offset]))
		{
			return null;
		}
		if (is_array($this->array[$offset]))
		{
			return new ArrayWrapperWithEscaping($this->array[$offset]);
		}
		return htmlentities($this->array[$offset], ENT_QUOTES, "UTF-8");
	}

	public function offsetSet($offset, $value)
	{
	}

	public function offsetUnset($offset)
	{
	}

	public function current()
	{
		return $this->offsetGet($this->currentIndex);
	}

	public function key()
	{
		return $this->currentIndex;
	}

	public function next()
	{
		$keys = array_keys($this->array);
		$nextKey = (int) array_search($this->currentIndex, $keys) + 1;
		$this->currentIndex = $keys[$nextKey];
	}

	public function rewind()
	{
		$keys = array_keys($this->array);
		$this->currentIndex = $keys[0];
	}

	public function valid()
	{
		return array_key_exists($this->currentIndex, $this->array);
	}

	public function inArray($needle)
	{
		return in_array($needle, $this->array);
	}
}

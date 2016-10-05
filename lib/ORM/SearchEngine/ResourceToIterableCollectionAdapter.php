<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM\SearchEngine;
class ResourceToIterableCollectionAdapter implements \Iterator, \Countable
{
	// dependencies //
	private $rowMapper;
	public function setRowMapper($f){$this->rowMapper = $f;}	
	
	// data //
	private $mysqlResource;
	private $index = 0;
	private $isValid = true;
	private $currentObject = null;

	public function setMySQLResource($r){ $this->mysqlResource = $r; }

	/* Iterator */
	public function next()
	{
		if($row = mysql_fetch_assoc($this->mysqlResource))
		{
			$this->currentObject = $this->rowMapper->mapRowToObject($row);
			$this->index += 1;
		}
		else
		{
			$this->isValid = false;
		}
	}
	
	public function rewind()
	{
		if ($this->count() > 0)
			mysql_data_seek($this->mysqlResource, 0);
		$this->isValid = true;
		$this->index = -1;
		$this->next();
	}

	public function count()
	{
		return mysql_num_rows($this->mysqlResource);
	}

	public function key(){ return $this->index; }
	public function valid() { return $this->isValid; }
	public function current(){ return $this->currentObject; }

	public function __destruct() { mysql_free_result($this->mysqlResource);	}
}

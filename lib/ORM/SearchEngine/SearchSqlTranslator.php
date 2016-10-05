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

class SearchSqlTranslator
{
	
	// data //
	private $targetTableName = null;
	private $targetTableAlias = null;
	private $criteria = array();
	private $sortingFields = array();
	private $limit = 100;
	private $offset = 0;
	private $criteriaClauses = array();
	private $joins = array();
	private $tables = array();


	public function setTargetTableName($table){ $this->targetTableName = $table; }
	public function setTargetTableAlias($alias){ $this->targetTableAlias = $alias; }
	public function setCriteria($criteria){ $this->criteria = $criteria;}
	public function setSortingFields($fields){ $this->sortingFields = $fields; }
	public function setLimit($limit) {$this->limit = $limit;}
	public function setOffset($offset) {$this->offset = $offset;}

	function getSearchSqlStatement()
	{
		$sorting_block 	= $this->_getSortingStatement();
		$where_block 	= $this->_getWhereStatement();
		$from_block		= $this->_getFromStatement();
		$select_block	= $this->_getSelectStatement();
		$limit_block	= $this->_getLimitBlock();
		$query = $select_block . $from_block . $where_block . $sorting_block . $limit_block;
//		echo "<tt>getSearchSqlStatement: $query</tt>";
		return $query;
	}
	
	function getSidsSqlStatement()
	{
		$sorting_block 	= $this->_getSortingStatement();
		$where_block 	= $this->_getWhereStatement();
		$from_block		= $this->_getFromStatement();
		$select_block	= $this->_getSidsStatement();
		$limit_block	= $this->_getLimitBlock();
		$query = $select_block . $from_block . $where_block . $sorting_block . $limit_block;
//		echo "<tt>getSearchSqlStatement: $query</tt>";
		return $query;
	}
	
	function getCountSqlStatement()
	{
		$where_block 	= $this->_getWhereStatement();
		$from_block		= $this->_getFromStatement();
		$select_block	= $this->_getCountStatement();
		$query = $select_block . $from_block . $where_block;
//		echo "<tt>getCountSqlStatement: $query</tt>";
		return $query;
	}
	
	function getCountGroupedBySqlStatement($columnName)
	{
		$where_block 	= $this->_getWhereStatement();
		$from_block		= $this->_getFromStatement();
		$select_block	= $this->_getCountGroupedByStatement($columnName);
		$group_by_block	= $this->_getGroupByStatement();
		$query = $select_block . $from_block . $where_block . $group_by_block;
//		echo "<tt>getCountSqlStatement: $query</tt>";
		return $query;
	}

	function _getSortingStatement()
	{
		if(empty($this->sortingFields) ) return null;
		$sortingClauses = array();
		foreach($this->sortingFields as $sortingData)
		{
			array_push($sortingClauses, $sortingData['property']->getOrderClause() . ' ' . $sortingData['direction']);
			if ($sortingData['property']->getTableAlias() != $this->targetTableAlias) $this->addJoin($sortingData['property']);
		}
		if (empty($sortingClauses)) return null;
		return ' ORDER BY ' . join(', ', $sortingClauses);
	}

	function _getSelectStatement()
	{
		$select_block = "SELECT `".$this->targetTableAlias."`.* ";
		return $select_block;
	}

	function _getSidsStatement()
	{
		$select_block = "SELECT `".$this->targetTableAlias."`.sid ";
		return $select_block;
	}

	function _getCountStatement()
	{
		$select_block = "SELECT COUNT(*)";
		return $select_block;
	}

	function _getCountGroupedByStatement($columnName)
	{
		$select_block = "SELECT `$columnName` as caption, COUNT(*) as count";
		return $select_block;
	}

	function _getGroupByStatement()
	{
		$group_by_block = " GROUP BY caption";
		return $group_by_block;
	}

	function _getFromStatement()
	{
		$from_block	 = " FROM `{$this->targetTableName}` AS `{$this->targetTableAlias}`" . join(" ", $this->joins) . " ";
		return $from_block;
	}

	function _getWhereStatement()
	{
		foreach($this->criteria as &$criterion)
		{
//			d('adding ' . $criterion . ' - ' . $criterion->isValid());
			if ($criterion->isValid())
			{
				array_push($this->criteriaClauses, $criterion->getSystemSQL());
				if ($criterion->getProperty()->getTableAlias() != $this->targetTableAlias) $this->addJoin($criterion->getProperty());
			}
		}
		if (empty($this->criteriaClauses)) return null;
		return 'WHERE ' . join(' AND ', array_unique($this->criteriaClauses));
	}

	function _getLimitBlock()
	{
		$block = null;
		if (!empty($this->limit))
		{
			$block = " LIMIT {$this->limit} "; 
			if (!empty($this->offset)) $block .= " OFFSET {$this->offset} ";
		}
		return $block;
	}
	
	function addJoin($property)
	{
		$foreignTableName = $property->getTableName();		
		$foreignTableAlias = $property->getTableAlias();		
		if (isset($this->joins[$foreignTableAlias]))
		{
			return; // only one join per table is allowed
		}

		$joinData = $property->getJoinCondition();
		$onClause = array();
		if (!isset($joinData[0]))
		{
			$onClause = $this->buildJoinCondition($joinData, $property);
		}
		else
		{
			foreach ($joinData as $singleJoinData) {
				$onClause[] = $this->buildJoinCondition($singleJoinData, $property);
			}
			$onClause = implode(' AND ', $onClause);
		}
		$this->joins[$foreignTableAlias] = " LEFT JOIN `$foreignTableName` AS `{$foreignTableAlias}` ON " . $onClause;
	}
	
	private function buildJoinCondition($joinData, $property)
	{
		$condition = array();
		foreach($joinData as $directive => $value)
		{
			switch ($directive) {
				case 'key_column':
					$condition[] = "`{$this->targetTableAlias}`.`{$value}`";
				break;
				case 'foriegn_column':
					$condition[] = "`{$property->getTableAlias()}`.`{$value}`";
				break;
				case 'value':
					$condition[] = "'{$value}'";
				break;
			}
		}
		
		return implode(' = ', $condition);
	}
}
?>

<?php
/**
 *
 *    Module: ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19789, 2016-06-17 13:19:41
 *
 *    This file is part of the 'ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\ip_blocklist\lib;

class IpRangeDBManager extends \lib\ORM\ObjectDBManager
{
    /**
     * getIpsInfo
     * @param array $filters Assoc array, keys: ipStart, ipEnd, ipMask
     * @param array $orderList Array of assoc arrays, keys: column, dir
     * @param array $pager Assoc array, keys: offset, rows
     * @return array
     */
    function getIpsInfo($filters, $orderList, $pager)
    {
        $sql  = 'SELECT * FROM `ip_blocklist_blocklist`';

        $part = $this->_renderFilterSqlPart($filters);
        if (!empty($part))
            $sql .= ' WHERE' . $part;

        $part = $this->_renderOrderSqlPart($orderList);
        if (!empty($part))
            $sql .= ' ' . $part;

        $part = $this->_renderLimitSqlPart($pager['offset'], $pager['rows']);
        if (!empty($part))
            $sql .= ' ' . $part;

		$objectsInfo = \App()->DB->query($sql);
        if (empty($objectsInfo))
            $objectsInfo = array();

		return $objectsInfo;
    }
    /**
     * renders LIMIT sql part 
     * @param int $offset
     * @param int $rows
     * @return string
     */
    function _renderLimitSqlPart($offset, $rows)
    {
        $sql = '';
        
        if (!is_null($offset) && !is_null($rows))
            $sql = sprintf('LIMIT %d, %d', $offset, $rows);

        return $sql;
    }
    /**
     * renders ORDER sql part
     * @param array $orderList Array of order nodes. Where node - array having two columns:
     *  column - table column name, dir - [ASC,DESC]
     * @return string
     */
    function _renderOrderSqlPart($orderList = array())
    {
        $sql = array();
        foreach($orderList as $ordering)
            $sql[] = $ordering['column'] . ' ' . $ordering['dir'];

        if (!empty($sql))
            $sql = 'ORDER BY ' . implode(', ', $sql);
        else
            $sql = '';

        return $sql;
    }
    /**
     * renders WHERE sql part
     * @param array $netAddress
     * @param int $netMask
     * @return string
     */
    function _renderFilterSqlPart($ipRange)
    {
        if ($ipRange['ipStart'] === false)
            return '';

        $ipStart = $ipRange['ipStart'];
        $ipEnd   = $ipRange['ipEnd'];
        $ipMask  = $ipRange['ipMask'];
        
        $sql = "(`start_ip` >= {$ipStart} AND `end_ip` <= {$ipEnd})";

        return $sql;
    }
    /**
     * saveIpRange
     * @param IpRange $ipRange
     */
	function saveIpRange($ipRange)
    {
		parent::saveObject($ipRange);
	}
    /**
     * getBannerInfoBySID
     * @param int $ipRangeSID
     * @return array|null
     */
    function getIpRangeInfoBySID($ipRangeSID)
    {
		return parent::getObjectInfo('ip_blocklist_blocklist', $ipRangeSID);
	}
    /**
     * deleteIpRangeInfo
     * @param int $ipRangeSID
     */
   	function deleteIpRangeInfo($ipRangeSID)
    {
        parent::deleteObject('ip_blocklist_blocklist', $ipRangeSID);
	}
    /**
     * getAllIpRangeSIDs
     * @return array
     */
    public function getAllIpRangeSIDs()
    {
		$sqlResult = \App()->DB->query('SELECT `sid` FROM `ip_blocklist_blocklist`');
        
        if (empty($sqlResult))
            $sqlResult = array();

        $result = array();
		foreach($sqlResult as $recordInfo)
			$result[] = $recordInfo['sid'];

		return $result;
    }
    /**
     * getAllIpRangesCount
     * @param array $filters Assoc array, keys: netAddress, netMask
     * @return int
     */
    function getAllIpRangesCount($filters)
    {
        $sql  = 'SELECT count(*) FROM `ip_blocklist_blocklist`';

        if ($filters)
        {
            $part = $this->_renderFilterSqlPart($filters);
            if (!empty($part))
                $sql .= ' WHERE' . $part;
        }

		$count = \App()->DB->getSingleValue($sql);
		return empty($count) ? null : $count;
    }
    /**
     * Returns number of records in ip range
     * @param int $ipStart
     * @param int $ipEnd
     * @return int|null
     */
    function isIpInBlockList($ipStart, $ipEnd)
    {
		$count = \App()->DB->getSingleValue('SELECT count(*) FROM `ip_blocklist_blocklist` WHERE(`start_ip` >= ?s AND `end_ip` <= ?s)', $ipStart, $ipEnd);
		return empty($count) ? null : $count;
    }
    /**
     * getAllIpAddressData
     * @return array
     */
    function getAllIpAddressData()
    {
        $sql  = 'SELECT `start_ip`, `end_ip`, `cidr_mask` FROM `ip_blocklist_blocklist`';

        $objectsInfo = \App()->DB->query($sql);
        if (empty($objectsInfo))
            $objectsInfo = array();

		return $objectsInfo;
    }
}
?>

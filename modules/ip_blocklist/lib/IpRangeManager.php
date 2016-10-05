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

/**
 * BannerManager
 */
class IpRangeManager implements \core\IService
{
    const ERROR_INVALID_SEARCH_IP = 'ERROR_INVALID_SEARCH_IP';

    /**
     * IpRangeDBManager
     * @var IpRangeDBManager
     */
    private $dbManager;
    /**
     * HtaccessManager
     * @var IpRangeHtaccessManager
     */
    private $htaccessManager;
    /**
     * Class initializer
     */
	public function init()
	{
		$this->dbManager = new \modules\ip_blocklist\lib\IpRangeDBManager();
        try
        {
            $this->htaccessManager = new IpRangeHtaccessManager('..');
        }
        catch(Exception $e)
        {
            $this->htaccessManager = false;
        }
	}
	/**
     * returns instance of IpRange
     * @param array $data
     * @return IpRange
     */
	public function createIpRange($data)
    {
		$ipRange = new IpRange();
		$ipRange->setDetails($this->_createIpRangeDetails($data));

		return $ipRange;
    }
    /**
     * returns array of all ip address in cidr notation
     * @return array
     */
    function getAllIpAddress()
    {
        $result = '';
        $ipData = $this->dbManager->getAllIpAddressData();
        foreach ($ipData as $ip)
            $result[] = IpProcessor::intToIp($ip['start_ip']) . '/' . $ip['cidr_mask'];

		return $result;
    }
    /**
     * returns all ip range sids
     * @return array
     */
    public function getAllIpRangeSIDs()
    {
		return $this->dbManager->getAllIpRangeSIDs();
    }
    /**
     * Returns blocked ip info
     * @param array $data
     * @param array $navgatorFilterData
     * @param array $navgatorOrderData
     * @param array $navgatorPagerData
     * @return array
     */
    public function getIpsInfo($data, &$navgatorFilterData = null, &$navgatorOrderData = null, &$navgatorPagerData = null)
    {
        $dataFilters = $this->_getNavigatorFilterData($data, $navgatorFilterData);
        $dataOrders  = $this->_getNavigatorOrdererData($data, $navgatorOrderData);
        $dataPages   = $this->_getNavigatorPagerData($data, $navgatorPagerData, $dataFilters);

        return $this->dbManager->getIpsInfo($dataFilters, $dataOrders, $dataPages);
    }
    /**
     * getIpRangeInfoBySID
     * @param int $ipRangeSID
     * @return array|null
     */
    public function getIpRangeInfoBySID($ipRangeSID)
    {
        return $this->dbManager->getIpRangeInfoBySID($ipRangeSID);
    }
    /**
     * selects ip range from db by $ipRangeSID, creates IpRange objects and returns it
     * @param int $ipRangeSID
     * @return IpRange|null
     */
    public function getIpRangeBySID($ipRangeSID)
    {
        $info = $this->getIpRangeInfoBySID($ipRangeSID);
        if (!is_null($info))
        {
            $info['ip_range'] = IpProcessor::intToIp($info['start_ip']) . '/' . $info['cidr_mask'];
            
            $info = $this->createIpRange($info);
            $info->setSID($ipRangeSID);
        }

        return $info;
    }
    /**
     * Saves $ipRange in to database
     * @param IpRange $ipRange
     */
    public function saveIpRange($ipRange)
    {
	    $this->defineCalculatedProperties($ipRange);
        $this->dbManager->saveIpRange($ipRange);
        
        if ($this->htaccessManager)
            $this->htaccessManager->importBlocklist($this->getAllIpAddress());
    }

	public function getIpSidByInfo($start, $end, $mask)
	{
		return \App()->DB->getSingleValue("SELECT `sid` FROM `ip_blocklist_blocklist` WHERE `start_ip` = ?s AND `end_ip` = ?s AND `cidr_mask` = ?n", $start, $end, $mask);
	}


    /**
     * deletes from database ip range by $ipRangeSID
     * @param int $ipRangeSID
     */
    public function deleteIpRangeBySID($ipRangeSID)
    {
        $this->dbManager->deleteIpRangeInfo($ipRangeSID);
        
        if ($this->htaccessManager)
            $this->htaccessManager->importBlocklist($this->getAllIpAddress());
    }
    /**
     * Returns structured array for template
     * @param array $data
     * @param array $navgatorFilterData
     * @param array $navgatorOrderData
     * @param array $navgatorPagerData
     * @return array
     */
    function createTemplateStructureForIpRanges($data, &$navgatorFilterData = null, &$navgatorOrderData = null, &$navgatorPagerData = null)
	{
		$structure = array();
		$ipsInfo = $this->getIpsInfo($data, $navgatorFilterData, $navgatorOrderData, $navgatorPagerData);

        foreach ($ipsInfo as $ipInfo)
            $structure[$ipInfo['sid']] = array(
                'sid'       => $ipInfo['sid'],
                'ip'        => IpProcessor::intToIp($ipInfo['start_ip']) . '/' . $ipInfo['cidr_mask'],
                'added' 	=> $ipInfo['added'],
                'comment'	=> $ipInfo['comment'],
            );

		return $structure;
	}
    /**
     * Creates and returns IpRange objects for import purpose
     * @param array $ipRangeInfo
     * @return IpRange
     */
    public function createObjectForImport($ipRangeInfo)
    {
		$ipRange = new IpRange();
		$ipRange->setDetails($this->_createIpRangeDetails($ipRangeInfo));
        $ipRange->addProperty(array(
            'id'	=> 'added',
            'type'  => 'datetime',
            'value'	=> (isset($ipRangeInfo['added']) && !empty($ipRangeInfo['added'])) ? \App()->I18N->getDateTime($ipRangeInfo['added']) : \App()->I18N->getDateTime(date('Y-m-d H:i:s')),
        ));

        return $ipRange;
    }
    /**
     * alias to getObjectBySID for data export
     * @param int $ipRangeSID
     * @return IpRange|null
     */
    public function getObjectForExportBySID($ipRangeSID)
	{
        $ipRange = null;
        $info = $this->getIpRangeInfoBySID($ipRangeSID);
        if (!is_null($info))
        {
            $info['ip_range'] = IpProcessor::intToIp($info['start_ip']) . '/' . $info['cidr_mask'];

            $ipRange = $this->createIpRange($info);
            $ipRange->addProperty(array(
                'id'	=> 'added',
                'type'  => 'date',
                'value'	=> $info['added'],
            ));
            $ipRange->setSID($info['sid']);
        }

        return $ipRange;
	}
    /**
     * Returns IP range properties
     * @return array
     */
	function getIpRangeProperties()
	{
		return array
		(
			array('id' => 'ip_range', 'caption' => 'IP / IP Range'),
			array('id' => 'added', 'caption' => 'Date added'),
			array('id' => 'comment', 'caption' => 'Comment'),
		);
	}
    /**
     * getAllIpRangesCount
     * @param array $filters Assoc array, keys: netAddress, netMask
     * @return int
     */
    function getAllIpRangesCount($filters)
    {
        return $this->dbManager->getAllIpRangesCount($filters);
    }
    /**
     * Returns boolean flag if $ipString exists in ip blocklist
     * @param int $ipString
     * @return bool
     */
    function isIpInBlockList($ipString)
    {
	    if (empty($ipString)) return false;

	    $ipProcessor = new IpProcessor($ipString);

        $result = $this->dbManager->isIpInBlockList(
            sprintf('%u', $ipProcessor->getIpRangeStartAsInt()),
            sprintf('%u', $ipProcessor->getIpRangeEndAsInt())
        );

        return (bool) $result;
    }
    /**
     * returns built instance of IpRangeDetails.
     * @param array $data
     * @return IpRangeDetails
     */
	private function _createIpRangeDetails($data)
	{
		$details = new IpRangeDetails();
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildPropertiesWithData($data);

		return $details;
	}
    /**
     * Extracts search filters
     * @param array $data
     * @param array $formData
     * @return array
     */
    private function _getNavigatorFilterData($data, &$formData = null)
    {
        $session = \App()->Session;
        $sessContainer = 'IpRangeNavigator';

        $filterData = array(
            'ipStart' => false,
            'ipEnd'   => false,
            'ipMask'  => false
        );
        
        $filterFormData = array(
            'filter_ip_range' => '',
            'filter_error'    => false
        );

        $reqVar = 'filter_ip_range';
        $locVar = 'filterIpRange';
        $$locVar = (isset($data[$reqVar])) ? $data[$reqVar] : $session->getValue($reqVar, $sessContainer);
        if (!is_null($$locVar) && !empty($$locVar))
        {
            try
            {
                $ip = new IpProcessor($$locVar);
                
                $filterFormData['filter_ip_range'] = $$locVar;

                $filterData['ipStart'] = sprintf('%u', $ip->getIpRangeStartAsInt());
                $filterData['ipEnd']   = sprintf('%u', $ip->getIpRangeEndAsInt());
                $filterData['ipMask']  = $ip->getIpRangeMaskAsCidr();
            }
            catch (Exception $e)
            {
                $$locVar = null;
                $filterFormData['filter_error'] = self::ERROR_INVALID_SEARCH_IP;
            }
        }
        $session->setValue($reqVar, $$locVar, $sessContainer);

        $formData = $filterFormData;

        return $filterData;
    }
    /**
     * Extracts order filters
     * @param array $data
     * @param array $formData
     * @return array
     */
    private function _getNavigatorOrdererData($data, &$formData = null)
    {
        $session = \App()->Session;
        $sessionContainer = 'IpRangeNavigator';

        $reqVarName = 'sorting_fields';
        $sortingFields = (isset($data[$reqVarName])) ? $data[$reqVarName] : $session->getValue($reqVarName, $sessionContainer);
        if (is_array($sortingFields) && count($sortingFields))
        {
            $sortingFieldNames = array_keys($sortingFields);
            $firstKey = array_shift($sortingFieldNames);
            $orderData = array(
                'column' => $firstKey,
                'dir'    => strtoupper($sortingFields[$firstKey]),
            );
        }
        else
        {
            $orderData = array(
                'column' => 'sid',
                'dir'    => 'ASC',
            );
        }

        $formData = $sortingFields;

        return array($orderData);
    }
    /**
     * Extracts page filters
     * @param array $data
     * @param array $formData
     * @return array
     */
    private function _getNavigatorPagerData($data, &$formData = null, $filters = null)
    {
        $session = \App()->Session;
        $sessContainer = 'IpRangeNavigator';
        $totalRows = $this->getAllIpRangesCount($filters);

        $reqVar = 'page_rows';
        $locVar = 'pageRows';
        $$locVar = (int) (isset($data[$reqVar])) ? $data[$reqVar] : $session->getValue($reqVar, $sessContainer);
        if (is_null($$locVar) || $$locVar < 10)
            $$locVar = 10;
        $session->setValue($reqVar, $$locVar, $sessContainer);

        $totalPages = ceil($totalRows / $pageRows);

        $reqVar = 'page_num';
        $locVar = 'pageNum';
        $$locVar = (int) (isset($data[$reqVar])) ? $data[$reqVar] : $session->getValue($reqVar, $sessContainer);
        if (is_null($$locVar) || $$locVar < 1 || $$locVar > $totalPages)
            $$locVar = 1;
        $session->setValue($reqVar, $$locVar, $sessContainer);

        $pagerFormData = array(
            'page_rows'  => $pageRows,
            'page_num'   => $pageNum,
            'page_total' => $totalPages
        );

        $pagerData = array(
            'offset' => $pageRows * ($pageNum - 1),
            'rows'   => $pageRows
        );

        $formData = $pagerFormData;
        
        return $pagerData;
    }

	/**
	 * @param IpRange $ipRange
	 */
	private function defineCalculatedProperties($ipRange)
	{
		$ipProcessor = new IpProcessor($ipRange->getPropertyValue('ip_range'));

		$ipRange->addProperty(array(
			'id' => 'start_ip',
			'type' => 'integer',
			'value' => sprintf('%u', $ipProcessor->getIpRangeStartAsInt()),
		));
		$ipRange->addProperty(array(
			'id' => 'end_ip',
			'type' => 'integer',
			'value' => sprintf('%u', $ipProcessor->getIpRangeEndAsInt()),
		));
		$ipRange->addProperty(array(
			'id' => 'cidr_mask',
			'type' => 'integer',
			'value' => $ipProcessor->getIpRangeMaskAsCidr(),
		));
	}
}

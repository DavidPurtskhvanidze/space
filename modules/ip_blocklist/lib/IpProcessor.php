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

class IpProcessor
{
    const ERROR_INVALID_ADDRESS = 'INVALID_IP_ADDRESS';
    const ERROR_INVALID_MASK    = 'INVALID_IP_MASK';

    /**
     * Network IP range start address
     * @var string
     */
    private $_ipRangeStart = null;
    /**
     * Network IP range end address
     * @var string
     */
    private $_ipRangeEnd = null;
    /**
     * Network IP range mask
     * @var int
     */
    private $_ipRangeMask = null;
    /**
     * Network IP range mask(CIDR notation)
     * @var int
     */
    private $_ipRangeCidrMask = null;
    /**
     * Object constructor
     * @param string $ipRange IP or IP range. Supported format:
     *  Full IP address
     *  Partial IP address
     *  Network/netmask pair
     *  Network/nnn CIDR specification
     */
    public function __construct($ipRange)
    {
        $this->_parseRange($ipRange);
    }
    /**
     * Returns start IP address as int
     * @return int
     */
    public function getIpRangeStartAsInt()
    {
        return $this->_ipRangeStart;
    }
    /**
     * Returns start IP address as int
     * @return int
     */
    public function getIpRangeEndAsInt()
    {
        return $this->_ipRangeEnd;
    }
    /**
     * Returns IP address range mask as int
     * @return int
     */
    public function getIpRangeMaskAsCidr()
    {
        return (int) $this->_ipRangeCidrMask;
    }
    /**
     * Checks if $ip in range
     * @param string $ip
     * @return bool
     */
    public function isInRange($ip)
    {
        return ((self::ipToInt($ip) & $this->_ipRangeMask) == $this->_ipRangeStart);
    }
    /**
     * Converts IP dotted string notation to int. Ex: '192.168.1.1' -> 3232235777
     * @param string $ipString
     * @return int|bool
     */
    public static function ipToInt($ipString)
    {
        return ip2long($ipString);
    }
    /**
     * Converts longint to dotted string IP notation. Ex: 3232235777 -> '192.168.1.1'
     * @param string $ipInt
     * @return string
     */
    public static function intToIp($ipInt)
    {
        return long2ip($ipInt);
    }
    /**
     * Returns clients IP address
     * @return string
     */
    public static function getClientIpAsString()
    {
	    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
	    {
		    if (array_key_exists($key, $_SERVER) === true)
		    {
			    foreach (explode(',', $_SERVER[$key]) as $ip)
			    {
				    $ip = trim($ip);
				    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false)
				    {
					    return $ip;
				    }
			    }
		    }
	    }
        return null;
    }
    /**
     * IP parser
     * @param string $ipRange IP or IP range. Supported format:
     *  Full IP address
     *  Partial IP address
     *  Network/netmask pair
     *  Network/nnn CIDR specification
     * @return bool
     */
    private function _parseRange($ipRange)
    {
        $netmask = false;
        if (strpos($ipRange, '/') === false) // Full or partial IP address
            $this->_ipRangeStart = $this->_fixRange($ipRange, $netmask);
        else
            @list($this->_ipRangeStart, $netmask) = explode('/', $ipRange, 2);

        if (strpos($netmask, '.') !== false) // $netmask is a 255.255.0.0 format
        {
            $netmask = str_replace('*', '0', $netmask);
            $netmask = ip2long($netmask);
        }
        elseif (intval($netmask) >= 1 && intval($netmask) <= 32)  // $netmask is nnn CIDR format
        {
            $netmask = ip2long('255.255.255.255') << (32 - intval($netmask));
            $netmask &= ip2long('255.255.255.255');
		}
        else
            $netmask = false;

        if (false === ($this->_ipRangeStart = self::ipToInt($this->_ipRangeStart)))
            throw new Exception(self::ERROR_INVALID_ADDRESS);

        if ((false === ($this->_ipRangeMask = $netmask)) || false !== strpos(sprintf('%032b', $netmask), '01'))
            throw new Exception(self::ERROR_INVALID_MASK);

        $this->_ipRangeCidrMask = ($netmask === -1) ? 32 : (32 - ceil(log(($netmask ^ ip2long('255.255.255.255')) + 1, 2)));
        if (is_nan($this->_ipRangeCidrMask))
            throw new Exception(self::ERROR_INVALID_MASK);

        $this->_ipRangeStart = $this->_ipRangeStart & $this->_ipRangeMask;
        $this->_ipRangeEnd = $this->_ipRangeStart | (ip2long('255.255.255.255') & (~$this->_ipRangeMask));
    }
    /**
     * Fixes short IP reprezentation: 192.168 to 192.168.0.0 and generates netmask as a CIDR notation. 16 for 192.168
     * @param string $ipRange
     * @param string $fixedNetmask Generated netmask as a CIDR notation. 16 for 192.168
     * @return string
     */
    private function _fixRange($ipRange, &$fixedNetmask = null)
    {
        $tmp = explode('.', $ipRange);
        $tmpNetmask = 32;
        while (count($tmp) < 4) {
            $tmp[] = '0';
            $tmpNetmask -= 8;
        }

        $fixedNetmask = $tmpNetmask;

        return implode('.', $tmp);
    }
}
?>

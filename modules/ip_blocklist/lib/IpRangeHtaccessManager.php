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
 * Description of IpRangeHtaccessManager
 *
 * @author Archer
 */
class IpRangeHtaccessManager {
    const FILE_MARK_DENY_FROM_START = '#IpBlocklistManager_Start';
    const FILE_MARK_DENY_FROM_END   = '#IpBlocklistManager_End';
    /**
     * Path to htaccess
     * @var string
     */
    private $_htaccessFile;

    public function __construct($path = '') {
        if (strlen($path) && ($path[strlen($path)-1] != '/'))
            $path .= '/' . '.htaccess';
        $this->_htaccessFile = $path;

        if (!file_exists($this->_htaccessFile) || !is_writeable($this->_htaccessFile))
            throw new Exception('File "' . $this->_htaccessFile. '" is not accessable or readonly');
    }
    /**
     * Reads .htaccess and extracts IP ranges between Start mark and stop mark.
     * @return array
     */
    private function _getBlocklist()
    {
        $pattern  = '/^';
        $pattern .= preg_quote(self::FILE_MARK_DENY_FROM_START);
        $pattern .= '(.*)';
        $pattern .= preg_quote(self::FILE_MARK_DENY_FROM_END);
        $pattern .= '/ms';

        $content = file_get_contents($this->_htaccessFile);

        $matches = array();
        $tmpBlocklist = '';
        if (preg_match($pattern, $content, $matches))
            $tmpBlocklist = $matches[1];

        $tmpBlocklist = explode("\n", $tmpBlocklist);

        $blocklist = array();
        foreach ($tmpBlocklist as $tmpIpRange)
        {
            $ipRange = false;
            if (preg_match('/^Deny from (.+)$/ui', $tmpIpRange, $matches))
                $ipRange = trim($matches[1]);

            if (!empty($ipRange))
                $blocklist[] = $ipRange;
        }

        return $blocklist;
    }
    /**
     * Clears blocklist data including start, end tags from $content
     * @param string $content
     * @return string
     */
    private function _clearBlocklistFromFileContent($content)
    {
        $pattern  = '/^';
        $pattern .= preg_quote(self::FILE_MARK_DENY_FROM_START);
        $pattern .= '(.*)';
        $pattern .= preg_quote(self::FILE_MARK_DENY_FROM_END);
        $pattern .= '/ms';

        return preg_replace($pattern, '', $content);
    }

    /**
     * Makes  Deny from option
     * @param string $ipRange
     * @return string
     */
    public function makeOptionDenyFrom($ipRange)
    {
        return 'Deny from '. $ipRange;
    }
    /**
     * imports array $ipBlocklist into htaccess file
     * @param array $ipRange
     */
	public function importBlocklist($ipBlocklist)
    {
		if (!empty($ipBlocklist))
		{
			$ipBlocklist = array_map(array($this, 'makeOptionDenyFrom'), $ipBlocklist);
	        $ipBlocklist = implode("\n", $ipBlocklist);
			$ipBlocklist = self::FILE_MARK_DENY_FROM_START . "\n" . $ipBlocklist . "\n" . self::FILE_MARK_DENY_FROM_END;
		}
		else
		{
			$ipBlocklist = self::FILE_MARK_DENY_FROM_START . "\n\n" . self::FILE_MARK_DENY_FROM_END;
		}
		
        $content = file_get_contents($this->_htaccessFile);

        $content = $this->_clearBlocklistFromFileContent($content);
        $content .= "\n" . $ipBlocklist;

        $content = file_put_contents($this->_htaccessFile, $content);
	}
}
?>

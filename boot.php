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

$underModFile = 'files/under_mod.txt';
if (file_exists($underModFile))
{
    $IPs = (string)file_get_contents($underModFile);
    $IPs = explode(',', $IPs);
    if (!in_array($_SERVER['REMOTE_ADDR'], $IPs))
        die('We apologize for the inconvenience, however, we are performing scheduled maintenance tasks on our server. The website will be back online shortly. Thank you very much for your patience!');
}

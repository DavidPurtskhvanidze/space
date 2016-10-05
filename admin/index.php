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


define("PATH_TO_ROOT", "../");

require_once "../vendor/autoload.php";
require_once "../core/WebApplication.php";

$app = new \core\WebApplication(
	PATH_TO_ROOT . "apps/AdminPanel/config/default.ini",
	PATH_TO_ROOT . "apps/AdminPanel/config/local.ini"
);
$app->run();

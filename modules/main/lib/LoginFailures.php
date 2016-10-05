<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\main\lib;

class LoginFailures
{
	private $ip;
	private $tableName = 'authentication_failures';
	private $time;
	private $limit;
	private $blockTime;
	static private $instance = null;

	static public function getInstance()
	{
		if (self::$instance === null) self::$instance = new LoginFailures();
		return self::$instance;
	}

	private function __construct()
	{
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->time = \App()->SettingsFromDB->getSettingByName('lf_time');
		$this->limit = \App()->SettingsFromDB->getSettingByName('lf_limit');
		$this->blockTime = \App()->SettingsFromDB->getSettingByName('lf_time_block');
		$this->checkban();
	}

	private function checkban()
	{
		if ($this->isNotHuman() && !$this->isBanned()) {
			\App()->DB->query("DELETE FROM `authentication_failures_blocklist` WHERE `ip` = ?s", $this->ip);
			\App()->DB->query("INSERT INTO `authentication_failures_blocklist`(`ip`, `time`) VALUES(?s, NOW())", $this->ip);
			$this->sendMessage();
		}
	}

	public function isNotHuman()
	{
		return $this->limit <= $this->getFailuresCount();
	}

	public function getAttemptsLeft()
	{
	    return $this->limit - $this->getFailuresCount();
	}

	public function isBanned()
	{
		return \App()->DB->getSingleValue("SELECT `time` FROM `authentication_failures_blocklist` WHERE `ip` = ?s AND `time` >  date_sub(now(), INTERVAL ?n MINUTE)", $this->ip, $this->blockTime);
	}

	public function sendMessage()
	{
		if (!\App()->DB->getSingleValue("SELECT `email_sended` FROM `authentication_failures_blocklist` WHERE `ip` = ?s", $this->ip)) {
			\App()->DB->query("UPDATE `authentication_failures_blocklist` SET  `email_sended` = 1 WHERE `ip` = ?s", $this->ip);

			return \App()->EmailService->sendToAdmin('email_template:password_guessing',
				array(
					'ip' => $this->ip,
					'username' => \App()->Request['admin_username'],
					'limit' => $this->limit,
					'Timestamp' => date('F j, Y, g:i a', time()),
				));
		}
	}

	public function incrementFailures()
	{
		\App()->DB->query("INSERT INTO `{$this->tableName}`(`ip`, `time`) VALUES(?s, NOW())", $this->ip);
		$this->checkban();
	}

	private function getFailuresCount()
	{
		$count = \App()->DB->getSingleValue("SELECT COUNT(*) FROM `{$this->tableName}` WHERE `ip` = ?s AND `time` >  date_sub(now(), INTERVAL ?n MINUTE)", $this->ip, $this->time);
		return $count;
	}

	public function delete()
	{
		\App()->DB->query("DELETE FROM `authentication_failures_blocklist` WHERE `ip` = ?s", $this->ip);
		\App()->DB->query("DELETE FROM `{$this->tableName}` WHERE `ip` = ?s", $this->ip);
	}

	/**
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * @return mixed
	 */
	public function getIp()
	{
		return $this->ip;
	}

	public function getBanExpiresTime()
	{
		$bannedTime = $this->isBanned();
		return date('Y-m-d H:i:s', strtotime($bannedTime) + 60 * \App()->SettingsFromDB->getSettingByName('lf_time_block'));
	}
} 

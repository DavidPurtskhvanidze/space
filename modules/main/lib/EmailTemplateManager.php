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


class EmailTemplateManager implements \core\IService
{
	private $tableName = 'email_templates';

	public function getEmailTemplateById($id)
	{
		$template = \App()->DB->getSingleRow("SELECT * FROM `{$this->tableName}` WHERE `id` = ?s", $id);
		return !empty($template) ? $template :  array('id' => $id, 'subject' => '', 'body' => '');
	}

	public function save($id, $subject, $body)
	{
		return \App()->DB->query("INSERT INTO `{$this->tableName}` (`id`, `subject`, `body`, `last_modified`) VALUES (?s, ?s, ?s, NOW())
						   ON DUPLICATE KEY UPDATE `subject` = ?s,`body` = ?s, `last_modified` = NOW()",
		$id, $subject, $body, $subject, $body);
	}

} 

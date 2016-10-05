<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class SuccessMessages extends Messages
{
	const MESSAGE_TYPE = "SUCCESS";

	public function addMessage($id, $data = array(), $moduleName = null)
	{
		$this->addMessageByType(array("id" => $id, "data" => $data), self::MESSAGE_TYPE, $moduleName);
	}
	public function fetchMessages()
	{
		return $this->fetchMessagesByType(self::MESSAGE_TYPE);
	}
	public function isEmpty()
	{
		$messages = $this->getMessages(self::MESSAGE_TYPE);
		return empty($messages);
	}
	public function deleteAllMessages()
	{
		$this->deleteAllMessagesByType(self::MESSAGE_TYPE);
	}
}

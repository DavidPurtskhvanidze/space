<?php
/**
 *
 *    Module: form_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: form_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19783, 2016-06-17 13:19:26
 *
 *    This file is part of the 'form_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\form_manager\lib;

class FormManager implements \core\IService
{
	var $table_prefix = "form_manager";

	public function getFormIDs($application_id)
	{
		$result = array();
		$forms_info = $this->getFormsInfo($application_id);
		foreach ($forms_info as $key => $info) 
		{
			$result[] = $info['id'];
		}
		return $result;
	}

	public function deleteFormByID($form_id, $application_id)
	{
		return \App()->DB->query("DELETE FROM `{$this->table_prefix}_forms` WHERE `id` = ?s AND `application_id` = ?s;", $form_id, $application_id);
	}

	public function deleteFormBySID($sid)
	{
		return \App()->DB->query("DELETE FROM `{$this->table_prefix}_forms` WHERE `sid` = '{$sid}';");
	}

	public function getFormsInfo($application_id)
	{
		return \App()->DB->query("SELECT * FROM `{$this->table_prefix}_forms` WHERE `application_id` = ?s;", $application_id);
	}

	public function getFormInfoByID($form_id, $application_id)
	{
        $result =  \App()->DB->query("SELECT * FROM `{$this->table_prefix}_forms` WHERE `id` = ?s AND `application_id` = ?s;", $form_id, $application_id);
		return end($result);
	}

	public function getFormInfoBySID($sid)
	{
        $form =  \App()->DB->query("SELECT * FROM `{$this->table_prefix}_forms` WHERE `sid` = ?s;", $sid);
		return end($form);
	}

	public function addForm($form_id, $title, $category_sid, $application_id)
	{
		return \App()->DB->query("INSERT INTO `{$this->table_prefix}_forms` SET `id` = ?s, `title` = ?s, `type` = ?s, `category_sid` = ?s, `application_id` = ?s;", $form_id, $title, 'search', $category_sid, $application_id);
	}

	public function getFieldInfo($form_sid, $field_id)
	{
		$item_info = \App()->DB->query("SELECT * FROM `{$this->table_prefix}_fields` WHERE `form_sid` = ?s AND `field_id` = ?s", $form_sid, $field_id);
		return !empty($item_info);
	}

	public function addField($form_sid, $field_id, $caption)
	{
		$max_order = \App()->DB->getSingleValue("SELECT MAX(`order`) FROM `{$this->table_prefix}_fields` WHERE `form_sid` = ?n", $form_sid);
		$max_order = empty($max_order) ? 0 : $max_order;
		return \App()->DB->query("INSERT INTO `{$this->table_prefix}_fields` SET `form_sid` = ?s, `field_id` = ?s, `caption` = ?s, `order` = ?n", $form_sid, $field_id, $caption, ++$max_order);
	}

	public function deleteField($form_sid, $sid)
	{
		return \App()->DB->query("DELETE FROM `{$this->table_prefix}_fields` WHERE `form_sid` = ?s AND `sid` = ?s", $form_sid, $sid);
	}

	public function saveField($form_sid, $title)
	{
		return \App()->DB->query("UPDATE `{$this->table_prefix}_forms` SET `title` = ?s WHERE `sid` = ?s", $title, $form_sid);
	}

	public function getFieldsInfo($application_id)
	{
		return \App()->DB->query("SELECT * FROM `{$this->table_prefix}_fields` WHERE `application_id` = ?s;", $application_id);
	}
	
	public function getFieldsInfoByFormSid($form_sid)
	{
		return \App()->DB->query("SELECT * FROM `{$this->table_prefix}_fields` WHERE `form_sid` = ?s ORDER BY `order`", $form_sid);
	}
}

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


namespace lib\ORM\Controllers;
class ListController
{
	// dependencies
	protected $FieldManager		= null;
	public function setFieldManager($FieldManager){$this->FieldManager = $FieldManager;}

	protected $ListItemManager	= null;
	public function setListItemManager($ListItemManager){$this->ListItemManager = $ListItemManager;}
	
	// data
	protected $field_sid 		= null;
	protected $field			= null;
	protected $field_info		= null;

	function setInputData($input_data)
	{
		if( isset($input_data['field_sid']) ) $this->field_sid = $input_data['field_sid'];
		if( !is_null($this->field_sid) ) $this->field = $this->FieldManager->getFieldBySID($this->field_sid);
		if( !is_null($this->field) ) $this->field_info = $this->FieldManager->getInfoBySID($this->field_sid);
	}

	function isvalidFieldSID()
	{
		return !is_null($this->field_sid) && !is_null($this->field);
	}

}
?>

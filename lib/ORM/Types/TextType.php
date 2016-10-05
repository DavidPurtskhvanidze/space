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


namespace lib\ORM\Types;


class TextType extends StringType
{
    function __construct(&$property_info)
	{                                  
		parent::__construct($property_info);
		$this->default_template = 'text.tpl';
	}

	function isValid()
	{
		if (!parent::isValid()) return false;

		$this->property_info['value'] = str_replace("\r\n", "\n", $this->property_info['value']);
		if (!empty($this->property_info['maxlength']) && strlen(utf8_decode($this->property_info['value'])) > $this->property_info['maxlength'])
		{
			$this->addValidationError('DATA_LENGTH_IS_EXCEEDED', array('maxLength' => $this->property_info['maxlength']));
			return false;
		}
		return true;
	}
	
	static function getFieldExtraDetails() {
		
		return array(
		
			array(
				'id'		=> 'maxlength',
				'caption'	=> 'Maximum Length', 
				'type'		=> 'integer',
				'length'	=> '20',
				'value'     => null,
				),
		
		);
		
	}

	public function getColumnDefinition(){ return 'LONGTEXT CHARACTER SET UTF8'; }
	
}

?>

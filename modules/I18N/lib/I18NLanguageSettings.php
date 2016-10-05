<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\lib;

class I18NLanguageSettings{
	function setContext($context){
		$this->context = $context;
	}
	function setDatasource($datasource){
		$this->datasource = $datasource;
	}
 	function getDecimalPoint(){
		$langData = $this->_getLangData();
 		return $langData->getDecimalSeparator();
	}
	function getThousandsSeparator(){
		$langData = $this->_getLangData();
 		return $langData->getThousandsSeparator();
	}
	function getDateFormat(){
		$langData = $this->_getLangData();
 		return $langData->getDateFormat();
	}
	function getTheme(){
		$langData = $this->_getLangData();
 		return $langData->getTheme();
	}
	function getMobileTheme(){
		$langData = $this->_getLangData();
 		return $langData->getMobileTheme();
	}
	function getAdminTheme(){
		$langData = $this->_getLangData();
 		return $langData->getAdminTheme();
	}

	function _getLangData(){
 		$langData = $this->datasource->getLanguageData($this->context->getLang());
 		return $langData;
	}

}
?>

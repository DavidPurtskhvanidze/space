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

class I18NContext{
	function setSettings(&$settings){
		$this->settings = $settings;
	}
	function setSession(&$session){
		$this->session = $session;
	}
	function setLanguageSettings(&$settings){
		$this->langSettings = $settings;
	}
	function setSystemSettings($settings){
		$this->systemSettings = $settings;
	}
	function setLangSwitcher(&$langSwitcher){
		$this->langSwitcher = $langSwitcher;
	}

	function getLang()
	{
		if($this->langSwitcher->isNeedSwitchLang())
		{
			$this->langSwitcher->execute();
		}
		return $this->session->getValue('lang');
	}
	function setLang($lang){
		return $this->session->setValue('lang', $lang);
	}
	function getDefaultLang(){
		return $this->settings->getSettingByName('i18n_default_language');
	}
	function getDefaultDomain(){
		return $this->settings->getSettingByName('i18n_default_domain');
	}
	function getHighlightedPattern(){
		return \App()->SystemSettings['I18NSettings_HighlightedPattern'];
	}
	function getAdminSiteUrl(){
		return \App()->SystemSettings->getSettingForApp('AdminPanel', 'SiteUrl');
	}
	function getSiteUrl(){
		return \App()->SystemSettings['SiteUrl'];
	}
	function getDefaultMode(){
		return $this->settings->getSettingByName('i18n_display_mode_for_not_translated_phrases');
	}
	function getPathToLanguageFiles(){
		return PATH_TO_ROOT . \App()->SystemSettings['I18NSettings_PathToLanguageFiles'];
	}
	function getFileNameTemplateForLanguageFile(){
		return \App()->SystemSettings['I18NSettings_FileNameTemplateForLanguageFile'];
	}
	function getDecimalPoint(){
		return $this->langSettings->getDecimalPoint();
	}
	function getThousandsSeparator(){
		return $this->langSettings->getThousandsSeparator();
	}
	function getDateFormat(){
		return $this->langSettings->getDateFormat();
	}
	function getTheme(){
		return $this->langSettings->getTheme();
	}
	function getMobileTheme(){
		return $this->langSettings->getMobileTheme();
	}
	function getAdminTheme(){
		return $this->langSettings->getAdminTheme();
	}
	function getLanguageIDMaxLength() {
		return \App()->SystemSettings['LanguageIDMaxLength'];
	}
	function getLanguageCaptionMaxLength() {
		return \App()->SystemSettings['LanguageCaptionMaxLength'];
	}
	function getDateFormatValidSymbols(){
		return \App()->SystemSettings['DateFormatValidSymbols'];
	}
	function getDateFormatMaxLength() {
		return \App()->SystemSettings['DateFormatMaxLength'];
	}
	function getValidThousandsSeparators() {
		return \App()->SystemSettings['ValidThousandsSeparators'];
	}
	function getValidDecimalsSeparators() {
		return \App()->SystemSettings['ValidDecimalsSeparators'];
	}
	function getPhraseIDMaxLength(){
		return \App()->SystemSettings['PhraseIDMaxLength'];
	}
	function getTranslationMaxLength(){
		return \App()->SystemSettings['TranslationMaxLength'];
	}
	function setDefaultLang($lang_id){
		return $this->settings->updateSetting('i18n_default_language', $lang_id);
	}
}
?>

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

class Translation2AdminWrapper
{
	var $repo;
	var $context;
		
	function setRepository($repo)
	{
		$this->repo = $repo;
	}
	
	function setContext($context)
	{
		$this->context = $context;
	}
	
	function get($phrase_id, $domain_id, $lang_id)
	{					
		$trAdmin = $this->repo->get($lang_id);
		return $trAdmin->get($phrase_id, $domain_id, $lang_id);
	}
	
	function addLang($lang_data)
	{		
		$default_lang = $this->context->getDefaultLang();
		$trDefaultLanguageAdmin = $this->repo->get($default_lang);
		$trAdmin = $this->repo->create($lang_data['lang_id']);
		$res = $trAdmin->addLang($lang_data);
		$domains = $trDefaultLanguageAdmin->getPageNames();
		$pages = array();
		foreach($domains as $domain)
		{
			$phrases = $trDefaultLanguageAdmin->getRawPage($domain, $default_lang);
			foreach(array_keys($phrases) as $phrase_id)
			{
				$trAdmin->add($phrase_id, $domain, array($lang_data['lang_id'] => null));
			}
		}
		return $res;
	}

	function updateLang($lang_data)
	{
		$trAdmin = $this->repo->get($lang_data['lang_id']);
		return $trAdmin->updateLang($lang_data);
	}
	
	function getLang($lang_id, $format)
	{
		$trAdmin = $this->repo->get($lang_id);
		return $trAdmin->getLang($lang_id, $format);
	}
	
	function removeLang($lang_id)
	{		
		return $this->repo->remove($lang_id);
	}
	
	function getLangs($format)
	{
		$lang_list = $this->repo->getLangList();
		
		$langs_data = array();
		
		foreach ($lang_list as $lang_id)
		{
			$langs_data[$lang_id] = $this->getLang($lang_id, $format);
		}
		
		return $langs_data;
	}
	
	function add($phrase_id, $domain_id, $translations) 
	{
		$result = true;
		
		foreach ($translations as $lang_id => $translation)
		{
			$translation = array($lang_id => $translation);
			
			$trAdmin = $this->repo->get($lang_id);
			$result &= $trAdmin->add($phrase_id, $domain_id, $translation);
		}
		
		return $result;
	}
	
	function update($phrase_id, $domain_id, $translations) 
	{
		$result = true;
		
		foreach ($translations as $lang_id => $translation)
		{
			$translation = array($lang_id => $translation);
			
			$trAdmin = $this->repo->get($lang_id);
			$result &= $trAdmin->update($phrase_id, $domain_id, $translation);
		}
		
		return $result;
	}
	
	function remove($phrase_id, $domain_id) 
	{
		$result = true;
		
		$lang_list = $this->repo->getLangList();
		
		foreach ($lang_list as $lang_id)
		{
			$trAdmin = $this->repo->get($lang_id);
			$result = $trAdmin->remove($phrase_id, $domain_id);
		}
		
		return $result;
	}
	
	function getPageNames()
	{
		$lang_id = $this->context->getDefaultLang();
		if(empty($lang_id))
		{
			trigger_error("Default Language not set");
			die;
		}
		$trAdmin = $this->repo->get($lang_id);
		if(is_a($trAdmin, "Pear_Error"))
		{
			trigger_error($trAdmin->getMessage());
		}
		return $trAdmin->getPageNames();
	}
	
	function getRawPage($domain_id, $lang_id)
	{
		$trAdmin = $this->repo->get($lang_id);
		return $trAdmin->getRawPage($domain_id, $lang_id);
	}
}


?>

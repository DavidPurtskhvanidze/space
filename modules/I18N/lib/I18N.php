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

class I18N  implements \core\IService
{
	function init()
	{
		$requestData = new \lib\Util\RequestData();

		$languageSettings = new I18NLanguageSettings();
		$context = new I18NContext();
		$admin = new I18NAdmin();
		$translator = new I18NTranslator();
		$i18nDatasource = new I18NDataSource();
		$datasource = $i18nDatasource->getInstance();
			
		$langSwitcher = new I18NSwitchLanguageAgent();
		
		$translationValidatorFactory = new \lib\Validation\TranslationValidatorFactory();
		$languageValidatorFactory = new \lib\Validation\LanguageValidatorFactory();
		$generalValidationFactory = new \lib\Validation\GeneralValidationFactory();
		$reflectionFactory = new \lib\Reflection\ReflectionFactory();
		
		$phraseSearcher = new I18NPhraseSearcher();
		$fullTextMatcher = new Util\FullTextMatcher();
		$phraseSearchCriteriaFactory = new I18NPhraseSearchCriteriaFactory();
		
		$formatterFactory = new I18NFormatterFactory();
		
		$fileHelper = new I18NFileHelper();

		$metadataManager = new metadataManager();

		$langSwitcher->setContext($context);
		$langSwitcher->setSession(\App()->Session);
		$langSwitcher->setRequestData($requestData);
		$langSwitcher->setI18N($this);

		$context->setSettings(\App()->SettingsFromDB);
		$context->setSession(\App()->Session);
		$context->setLanguageSettings($languageSettings);
		$context->setSystemSettings(\App());
		$context->setLangSwitcher($langSwitcher);
		
		$fileSystem = \App()->ObjectMother->createFileSystem();
		$fileHelper->setContext($context);
		$fileHelper->setFileSystem($fileSystem);
		
		$datasource->init($context, $fileHelper);
		$admin->setDataSource($datasource);
		
		$languageSettings->setContext($context);
		$languageSettings->setDataSource($datasource);

		$translator->setContext($context);
		$translator->setDatasource($datasource);
		
		$languageValidatorFactory->setContext($context);
		$languageValidatorFactory->setGeneralValidationFactory($generalValidationFactory);
		$languageValidatorFactory->setReflectionFactory($reflectionFactory);
		$languageValidatorFactory->setLanguageDataSource($datasource);

		$translationValidatorFactory->setContext($context);
		$translationValidatorFactory->setGeneralValidationFactory($generalValidationFactory);
		$translationValidatorFactory->setReflectionFactory($reflectionFactory);
		$translationValidatorFactory->setLanguageDataSource($datasource);
		
		$phraseSearcher->setDataSource($datasource);
		$phraseSearcher->setMatcher($fullTextMatcher);
		
		$formatterFactory->setContext($context);
		

		$this->setTranslator($translator);
		$this->setAdmin($admin);
		$this->setContext($context);
		$this->setLanguageValidatorFactory($languageValidatorFactory);
		$this->setTranslationValidatorFactory($translationValidatorFactory);
		$this->setReflectionFactory($reflectionFactory);
		$this->setPhraseSearcher($phraseSearcher);
		$this->setPhraseSearchCriteriaFactory($phraseSearchCriteriaFactory);
		$this->setFormatterFactory($formatterFactory);
		$this->setFileHelper($fileHelper);
		$this->setMetadataManager($metadataManager);
	}

	
	function setTranslator(&$translator){
		$this->translator = $translator;
	}
	function setAdmin(&$admin){
		$this->admin = $admin;
	}
	function setContext(&$context){
		$this->context = $context;
	}
	function setLanguageValidatorFactory(&$factory)
	{
		$this->languageValidatorFactory =$factory;
	}
	function setTranslationValidatorFactory(&$factory)
	{
		$this->translationValidatorFactory =$factory;
	}
	function setReflectionFactory(&$factory)
	{
		$this->reflectionFactory =$factory;
	}
	function setPhraseSearcher(&$phraseSearcher)
	{
		$this->phraseSearcher =$phraseSearcher;
	}
	function setPhraseSearchCriteriaFactory(&$phraseSearchCriteriaFactory)
	{
		$this->phraseSearchCriteriaFactory = $phraseSearchCriteriaFactory;
	}
	function setFormatterFactory(&$formatterFactory)
	{
		$this->formatterFactory = $formatterFactory;
	}
	function setFileHelper(&$fileHelper)
	{
		$this->fileHelper = $fileHelper;
	}
	function setMetadataManager(&$metadataManager)
	{
		$this->metadataManager = $metadataManager;
	}
	function getFileHelper()
	{
		return $this->fileHelper;
	}
	
	function gettext($domain_id, $phrase_id, $mode)
	{
		$res = $this->translator->gettext($domain_id, $phrase_id, $mode);
		if (is_object($res))
		{
			\core\Logger::error($res->getError());
			return $phrase_id;
		}
		return $res;
	}
	
	function getInt($number)
	{
		$formatter = $this->formatterFactory->getIntFormatter();
		return $formatter->getOutput($number);
	}

	function getFloat($number, $decimals = null)
	{
		$formatter = $this->formatterFactory->getFloatFormatter();
		$formatter->setDecimals($decimals);
		return $formatter->getOutput($number);
	}

	function getDate($date)
	{
		$formatter = $this->formatterFactory->getDateFormatter();
		return $formatter->getOutput($date);
	}

	public function getDateTime($value)
	{
		$formatter = $this->formatterFactory->getDateTimeFormatter();
		return $formatter->getOutput($value);
	}

	function getInput($type, $value)
	{
		if (!$this->formatterFactory->doesFormatterExist($type))
		{
			\core\Logger::error('UNDEFINED_TYPE');
			return $value;
		}
		
		$formatter = $this->formatterFactory->getFormatter($type);
		return $formatter->getInput($value);
	}
	
	function &getContext()
	{
		return $this->context;
	}
	
	function isValidFloat($value)
	{
		$formatter = $this->formatterFactory->getFloatFormatter();
		return $formatter->isValid($value);
	}
	
	function isValidInteger($value)
	{
		$formatter = $this->formatterFactory->getIntFormatter();
		return $formatter->isValid($value);
	}
	
	function isValidDate($value)
	{
		$formatter = $this->formatterFactory->getDateFormatter();
		return $formatter->isValid($value);
	}

	public function isValidDateTime($value)
	{
		$formatter = $this->formatterFactory->getDateTimeFormatter();
		return $formatter->isValid($value);
	}

	function &getDomainsData()
	{		
		$domainsData = $this->admin->getDomainsData();
		$result = array();
		for ($i = 0; $i < count($domainsData); $i++)
		{
			$result[] = $domainsData[$i]->getID();
		}
		return $result;
	}
	
	function &searchPhrases(&$criteria)
	{		
		$phrasesData = $this->phraseSearcher->search($criteria);
		$phrases_data = array();
		foreach (array_keys($phrasesData) as $i)
		{
			$phraseData = $phrasesData[$i];
			
			$translationsData = $phraseData->getTranslations();
			$translations = array();
			foreach ($translationsData as $key => $value)
			{
				$translationData = $translationsData[$key];
				$translations[$translationData->getLanguageID()] = $translationData->getTranslation();
			}
			$phrase_data = array
			(
				'id'			=> $phraseData->getID(),
				'domain'		=> $phraseData->getDomainID(),
				'translations'	=> $translations,
			);
			
			$phrases_data[] = $phrase_data;
		}

		return $phrases_data;
	}
	
	function &getPhraseSearchCriteriaFactory()
	{
		return $this->phraseSearchCriteriaFactory;
	}
	
	function phraseExists($phraseId, $domainId) 
	{
		$domainExistsValidator = $this->translationValidatorFactory->createDomainExistsValidator();
		
		$dataReflector = $this->reflectionFactory->createConstantReflector($domainId);		
		$phraseExistsValidator = $this->translationValidatorFactory->createPhraseExistsValidator();
		$phraseExistsValidator->setDataReflector($dataReflector);
		
		return $domainExistsValidator->isValid($domainId) && $phraseExistsValidator->isValid($phraseId);
	}
    
	function translationIsValid($translations)
	{
		return true;
	}
	
	function addPhrase($phrase_data) 
	{		
		$phraseData = Data\PhraseData::createPhraseDataFromClient($phrase_data);
		return $this->admin->addPhrase($phraseData);
	}	
	
	function updatePhrase($phrase_data) 
	{
		$phraseData = Data\PhraseData::createPhraseDataFromClient($phrase_data);
		return $this->admin->updatePhrase($phraseData);
	}	
	
	function deletePhrase($phrase_id, $domain_id) 
	{
		return $this->admin->deletePhrase($phrase_id, $domain_id);
	}	
	
	function getPhraseData($phrase_id, $domain_id)
	{
		$phraseData = $this->admin->getPhraseData($phrase_id, $domain_id);
		
		$translations = array();
		$translationsData = $phraseData->getTranslations();
		
		foreach ($translationsData as $key => $value)
		{
			$translationData = $translationsData[$key];
			$translations[$translationData->getLanguageID()] = $translationData->getTranslation();
		}
		
		$phrase_data = array
		(
			'id'			=> $phraseData->getID(),
			'domain'		=> $phraseData->getDomainID(),
			'translations'	=> $translations,
		);
		
		return $phrase_data;
	}
	
	function &createAddTranslationValidator($translations)
	{
		$validator =$this->translationValidatorFactory->createAddTranslationValidator($translations);
		return $validator;
	}
	
	function &createUpdateTranslationValidator($translations)
	{
		$validator =$this->translationValidatorFactory->createUpdateTranslationValidator($translations);
		return $validator;
	}
	
	/********** L A N G U A G E S **********/
	function addLanguage($lang_data) 
	{
		$langData = Data\LangData::createLangDataFromClient($lang_data);
		$this->admin->addLanguage($langData);
	}
	
	function getDateFormat()
	{
		$dateFormat = $this->context->getDateFormat();
		$dateFormat = str_replace('%m', $this->gettext(null, 'month', null), $dateFormat);
		$dateFormat = str_replace('%d', $this->gettext(null, 'day', null), $dateFormat);
		$dateFormat = str_replace('%Y', $this->gettext(null, 'year', null), $dateFormat);
		return $dateFormat;
	}
	
	function getRawDateFormat()
	{
		return $this->context->getDateFormat();
	}

	function getLanguageData($lang_id) 
	{		
		$langData = $this->admin->getLanguageData($lang_id);		
		
		$lang_data = array
		(
			'id' 					=> $langData->getID(),
			'caption' 				=> $langData->getCaption(),
			'active' 				=> $langData->getActive(),
			'is_default' 			=> $this->context->getDefaultLang() === $langData->getID(),
			'theme' 				=> $langData->getTheme(),
			'mobile_theme' 			=> $langData->getMobileTheme(),
			'admin_theme' 			=> $langData->getAdminTheme(),
			'date_format' 			=> $langData->getDateFormat(),
			'decimal_separator' 	=> $langData->getDecimalSeparator(),
			'thousands_separator' 	=> $langData->getThousandsSeparator(),	
		);
		
		return $lang_data;
	}
	
	function updateLanguage($lang_data)
	{
		$langData = Data\LangData::createLangDataFromClient($lang_data);
		$this->admin->updateLanguage($langData);
	}	
	
	function deleteLanguage($lang_id)
	{
		return $this->admin->deleteLanguage($lang_id);
	}
		
	function getLanguagesData() 
	{
		$langs_data = array();
		$langsData = $this->admin->getLanguagesData();
		
		foreach($langsData as $langData)
		{
			$langs_data[] = array
			(
				'id' 					=> $langData->getID(),
				'caption' 				=> $langData->getCaption(),
				'active' 				=> $langData->getActive(),
				'is_default' 			=> $this->context->getDefaultLang() === $langData->getID(),
				'theme' 				=> $langData->getTheme(),
				'date_format' 			=> $langData->getDateFormat(),
				'decimal_separator' 	=> $langData->getDecimalSeparator(),
				'thousands_separator' 	=> $langData->getThousandsSeparator(),	
			);
		}
		
		return $langs_data;	
	}
		
	function getActiveLanguagesData() 
	{
		$langs_data = array();
		$langsData = $this->admin->getLanguagesData();
		
		foreach($langsData as $langData)
		{
			$lang_is_active = $langData->getActive();
			
			if ($lang_is_active)
			{
				$langs_data[] = array
				(
					'id' 					=> $langData->getID(),
					'caption' 				=> $langData->getCaption(),
					'active' 				=> $langData->getActive(),
					'is_default' 			=> $this->context->getDefaultLang() === $langData->getID(),
					'theme' 				=> $langData->getTheme(),
					'date_format' 			=> $langData->getDateFormat(),
					'decimal_separator' 	=> $langData->getDecimalSeparator(),
					'thousands_separator' 	=> $langData->getThousandsSeparator(),	
				);
			}
		}
		
		return $langs_data;	
	}
	
	function languageExists($lang_id) 
	{
		$validator = $this->languageValidatorFactory->createLanguageExistsValidator();
		return $validator->isValid($lang_id);
	}
	
	function isLanguageActive($lang_id)
	{
		$validator = $this->languageValidatorFactory->createLanguageIsActiveValidator();
		return $validator->isValid($lang_id);
	}
	
	function setDefaultLanguage($lang_id) 
	{
		$this->context->setDefaultLang($lang_id);
	}
	
	function getCurrentLanguage()
	{
		return $this->context->getLang();
	}

	function getCurrentLanguageTheme()
	{
		return $this->context->getTheme();
	}
	
	function getCurrentLanguageMobileTheme()
	{
		return $this->context->getMobileTheme();
	}

	function getCurrentLanguageAdminTheme()
	{
		return $this->context->getAdminTheme();
	}

	public function getCurrentLanguageThemeForApp($appId)
	{
		if ($appId == "AdminPanel") return $this->getCurrentLanguageAdminTheme();
		if ($appId == "FrontEnd") return $this->getCurrentLanguageTheme();
		if ($appId == "MobileFrontEnd") return $this->getCurrentLanguageMobileTheme();
		return null;

	}

    function getCurrentLanguageDecimalSeparator()
    {
        return $this->context->getDecimalPoint();
    }

    function getCurrentLanguageThousandsSeparator()
    {
        return $this->context->getThousandsSeparator();
    }

	function &createAddLanguageValidator($lang_data)
	{
		$validator =$this->languageValidatorFactory->createAddLanguageValidator($lang_data);
		return $validator;
	}

	function &createUpdateLanguageValidator($lang_data)
	{
		$validator =$this->languageValidatorFactory->createUpdateLanguageValidator($lang_data);
		return $validator;
	}

	function &createDeleteLanguageValidator($lang_id)
	{
		$validator =$this->languageValidatorFactory->createDeleteLanguageValidator($lang_id);
		return $validator;
	}
	
	function &createSetDefaultLanguageValidator($lang_id)
	{
		$validator =$this->languageValidatorFactory->createSetDefaultLanguageValidator($lang_id);
		return $validator;
	}
	
	function &createImportLanguageValidator($lang_file_data)
	{
		$validator =$this->languageValidatorFactory->createImportLanguageValidator($lang_file_data);
		return $validator;
	}

	function &getDomainPhrases($domainId)
	{
		$data = $this->admin->getDomainPhrases($domainId);
		return $data;
	}
		
	function importLangFile($file_name, $file_path)
	{
		$lang_files_path = $this->context->getPathToLanguageFiles();
		$dest_file_path = \App()->Path->combine($lang_files_path, $file_name);
		
		\App()->FileSystem->copy($file_path, $dest_file_path);
		\App()->FileSystem->deleteFile($file_path);

		return true;
	}
	
	function getFilePathToLangFile($lang_id)
	{
		return $this->fileHelper->getFilePathToLangFile($lang_id);
	}

	function translate($params, $phrase_id, &$smarty, $repeat)
	{
		if($repeat) return null; // see Smarty manual

		//$i18n =I18N::getInstance();

		$mode = isset($params['mode']) ? $params['mode'] : null;

		if (isset($params['resolveMetadataFor']) && !empty($params['resolveMetadataFor'])){
			$this->_resolveParams($params['resolveMetadataFor'], $params, $smarty);
		}

		if (isset($params['type'])) {
			$decimals = isset($params['signs_num']) ? $params['signs_num'] : null;
			return $this->_translateByType($params['type'], $decimals, $phrase_id);
		} 

		$phrase_id = trim($phrase_id);

		$domain = isset($params['domain']) ? $params['domain'] : null;
		
		$res = $this->gettext($domain, $phrase_id, $mode);
		$res = $this->replace_with_template_vars($res, $smarty);
		return $res;
	}
	
	function replace_with_template_vars($res, &$smarty){
		if(preg_match_all("/{[$]([a-zA-Z0-9_]+)}/", $res, $matches)){
			foreach($matches[1] as $varName){
				$value = $smarty->getTemplateVars($varName);
				$res = preg_replace("/{[$]".$varName."}/", (string) $value, $res);
			}
		}
		return $res;
	}

	function _resolveParams($id, &$params, $smarty){
		$metadata = $this->metadataManager->getMetadata($id, $smarty);
		if(!empty($metadata))
			$params = array_merge($params, $metadata);
	}

	function _translateByType($type, $decimals, $value)
	{
		switch ($type) {
			case 'int':
			case 'integer':
				return $this->getInt($value);
				break;
			case 'float':
				return $this->getFloat($value, $decimals);
				break;
			case 'date':
				return $this->getDate($value);
				break;
			case 'datetime':
				return $this->getDateTime($value);
				break;
			default: return $value;
				break;
		}
		return null;
	}

	function replace_translation_alias($tpl_source) 
	{
		return preg_replace_callback
		(
			'/\[\[(?:([\w-_]+)!)?(.*?)(?::([\w-_]+))?\]\]/ms',
			
			array
			(
				&$this, '_replace_alias_with_block_function_tr'
			),
			$tpl_source);
	}

	function _replace_alias_with_block_function_tr($matches) 
	{
		$domain = $matches[1];
		$phrase_id = $matches[2];
		$mode = isset($matches[3]) ? ' mode="'.$matches[3].'"' : null;
		$metadata = null;
		$resolveMetadataFor = null;
		if(preg_match("/^[$]([\w.]+)$/",$phrase_id, $m)){
			$resolveMetadataFor = " resolveMetadataFor = '" .$m[1]. "'";
			$phrase_id = "{".$phrase_id."}";
		}else
		if($domain){
			$domain = ' domain="'.$domain.'"';
		}elseif(preg_match("/^(\w+\\\\!)/", $phrase_id)) {
			$phrase_id = preg_replace("/^(\w+)\\\\!/", '$1!', $phrase_id);
		}
		if($phrase_id)
		return sprintf("{tr%s%s%s}%s{/tr}", $resolveMetadataFor, $domain, $mode, $phrase_id);
	}

	public function fetchAutocompleteOptionsForPhraseIds($keyword, $maxRows)
	{
		$criteria = array(
			'phrase_id' => $keyword,
		);
		$phraseSearchCriteria = $this->getPhraseSearchCriteriaFactory()->create($criteria);
		
		$dataSet = $this->searchPhrases($phraseSearchCriteria);
		$result = array();
		foreach($dataSet as $record)
		{
			preg_match_all('/[\p{L}\p{N}]*' . preg_quote($keyword) . '[\p{L}\p{N}]*/iu', $record['id'], $matches, PREG_PATTERN_ORDER);
			if (!empty($matches))
			{
				foreach ($matches[0] as $value)
				{
					$result[$value] = 1;
				}
				if (count($result) > $maxRows)
				{
					break;
				}
			}
		}
		$result = array_keys($result);
		$result = array_map(
			function($value)
			{
				return array(
					'value' => $value,
					'label' => $value,
				);
			},
			$result
		);
		
		return $result;
	}
}

class metadataManager
{
	function getMetadata($id, $smarty)
	{
		list($var, $propertyName) = explode('.', $id);
		$varValue = $smarty->getTemplateVars($var);

		$metadataProviders = new \core\ExtensionPoint('modules\I18N\IMetadataProvider');
		foreach ($metadataProviders as $metadataProvider)
		{
			if ($var == $metadataProvider->getVarName())
			{
				return $metadataProvider->getMetadata($propertyName, $varValue);
			}
		}

		if($var == 'browseItem' && isset($varValue['propertyDomain']))
		{
			return array('domain' => $varValue['propertyDomain']);
		}
		if($var == 'element')
		{
			if($varValue['metadata']['domain'] == 'Property_category_sid')
			{
				return array('domain' => 'Categories');
			}
			else
			{
				return array('domain' => $varValue['metadata']['domain']);
			}
		}
		if($var == 'article' && $propertyName == 'date')
		{
			$res = array('type' => 'datetime', 'property' => array());
			return $res;
		}
		if($var == 'form_fields' || $var == 'formField')
		{
			return array('domain' => 'FormFieldCaptions');
		}
		if($var == 'listing_package')
		{
			return array('domain' => 'Miscellaneous');
		}
		if(in_array($var, array('category', 'node', 'ancestor')))
		{
			return array('domain' => 'Categories');
		}
		if(in_array($var, array('form_field')))
		{
			return array('domain' => 'FormFieldCaptions');
		}
		if(in_array($var, array('user_group_info')))
		{
			return array('domain' => 'Miscellaneous');
		}
		return array();
	}
}
?>

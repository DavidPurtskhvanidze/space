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


namespace lib\Validation;

class TranslationValidatorFactory
{
	var $generalValidationFactory;
	
	function &createAddTranslationValidator($translation_data)
	{	
		$dataReflector = $this->reflectionFactory->createHashtableReflector($translation_data);
		$this->setDataReflector($dataReflector);
		
		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->generalValidationFactory->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('domainId', 'PhraseDomainValidator', 'INVALID_DOMAIN_NAME');
		$batch->add('phraseId', 'NotEmptyValidator', 'PHRASE_ID_IS_EMPTY');
		$batch->add('phraseId', 'PhraseIDLengthValidator', 'TOO_LONG_PHRASE_ID');
		$batch->add('phraseId', 'PhraseNotExistsValidator', 'PHRASE_ALREADY_EXISTS');
		for($i = 0; $i < count($translation_data['translations']); $i++){
			$batch->add("['translations'][$i]['LanguageId']", 'LanguageExistsValidator', 'LANGUAGE_NOT_EXISTS');
			$batch->add("['translations'][$i]['Translation']", 'TranslationLengthValidator', 'TOO_LONG_TRANSLATION');
			$batch->add("['translations'][$i]['LanguageId']", 'LanguageFileIsWritableValidator', 'LANGUAGE_FILE_IS_NOT_WRITABLE');
		}
		return $batch;
	}

	function &createUpdateTranslationValidator($translation_data)
	{	
		$dataReflector = $this->reflectionFactory->createHashtableReflector($translation_data);
		$this->setDataReflector($dataReflector);
		
		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->generalValidationFactory->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('phraseId', 'PhraseExistsValidator', 'PHRASE_NOT_EXISTS');
		$batch->add('domainId', 'DomainExistsValidator', 'DOMAIN_NOT_EXISTS');
		for($i = 0; $i < count($translation_data['translations']); $i++){
			$batch->add("['translations'][$i]['LanguageId']", 'LanguageExistsValidator', 'LANGUAGE_NOT_EXISTS');
			$batch->add("['translations'][$i]['Translation']", 'TranslationLengthValidator', 'TOO_LONG_TRANSLATION');
			$batch->add("['translations'][$i]['LanguageId']", 'LanguageFileIsWritableValidator', 'LANGUAGE_FILE_IS_NOT_WRITABLE');
		}
		return $batch;
	}

	function setDataReflector(&$dataReflector){
		$this->dataReflector = $dataReflector;
	}

	function setLanguageDataSource(&$langDataSource)
	{
		$this->langDataSource = $langDataSource;
	}
	
	function setReflectionFactory(&$reflectionFactory)
	{
		$this->reflectionFactory = $reflectionFactory;
	}
	
	function setContext(&$context)
	{
		$this->context = $context;
	}
	function setGeneralValidationFactory(&$generalValidationFactory)
	{
		$this->generalValidationFactory = $generalValidationFactory;
	}

	function createPhraseIDLengthValidator()
	{
		$validator = $this->generalValidationFactory->createMaxLengthValidator($this->context->getPhraseIDMaxLength());
		return $validator;
	}

	function createPhraseExistsValidator()
	{
				$validator = new PhraseExistsValidator();
		$validator->setLanguageDataSource($this->langDataSource);
		$validator->setDataReflector($this->dataReflector);
		return $validator;
	}

	function createPhraseNotExistsValidator()
	{
		$source_validator = $this->createPhraseExistsValidator();
		$validator = $this->generalValidationFactory->createNotValidator($source_validator);
		return $validator;
	}

	function createPhraseDomainValidator()
	{
				return new PhraseDomainValidator();
	}

	function createDomainExistsValidator()
	{
				$validator = new DomainExistsValidator();
		$validator->setLanguageDataSource($this->langDataSource);
		return $validator;
	}

	function createLanguageExistsValidator()
	{
				$validator = new LanguageExistsValidator();
		$validator->setLanguageDataSource($this->langDataSource);
		return $validator;
	}

	function createLanguageFileIsWritableValidator()
	{
		$validator = new LanguageFileIsWritableValidator();
		return $validator;
	}

	function createTranslationLengthValidator()
	{
		$validator = $this->generalValidationFactory->createMaxLengthValidator($this->context->getTranslationMaxLength());
		return $validator;
	}

	function createNotEmptyValidator()
	{
		$validator = $this->generalValidationFactory->createNotEmptyValidator();
		return $validator;
	}

}

?>

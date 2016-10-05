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

class LanguageValidatorFactory
{
	var $generalValidationFactory;
	var $dataReflector;
	
	function &createAddLanguageValidator($lang_data)
	{	
		$dataReflector = $this->reflectionFactory->createHashtableReflector($lang_data);
		$this->setDataReflector($dataReflector);
		
		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->generalValidationFactory->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('languageId', 'NotEmptyValidator', 'LANGUAGE_ID_IS_EMPTY');
		$batch->add('languageId', 'IDSymbolsValidator', 'LANGUAGE_ID_CONTAINS_NOT_ALLOWED_SYMBOLS');
		$batch->add('languageId', 'LanguageIDLengthValidator', 'TOO_LONG_LANGUAGE_ID');
		$batch->add('languageId', 'LanguageNotExistsValidator', 'LANGUAGE_ALREADY_EXISTS');
		$batch->add('caption', 'LanguageCaptionLengthValidator', 'TOO_LONG_LANGUAGE_CAPTION');
		$batch->add('caption', 'NotEmptyValidator', 'LANGUAGE_CAPTION_IS_EMPTY');
		$batch->add('date_format', 'DateFormatLengthValidator', 'TOO_LONG_DATE_FORMAT');
		$batch->add('date_format', 'DateFormatValidator', 'INVALID_DATE_FORMAT');
		$batch->add('decimal_separator', 'DecimalsSeparatorValidator', 'INVALID_DECIMALS_SEPARATOR');
		$batch->add('thousands_separator', 'ThousandsSeparatorValidator', 'INVALID_THOUSANDS_SEPARATOR');
		$batch->add('thousands_separator', 'DifferentThousandsAndDecimalSeparatorsValidator', 'THOUSANDS_AND_DECIMAL_SEPARATORS_MUST_BE_DIFFERENT');
		return $batch;
	}
	
	
	function &createUpdateLanguageValidator($lang_data)
	{
		$dataReflector = $this->reflectionFactory->createHashtableReflector($lang_data);
		$this->setDataReflector($dataReflector);
		
		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->generalValidationFactory->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('caption', 'LanguageCaptionLengthValidator', 'TOO_LONG_LANGUAGE_CAPTION');
		$batch->add('caption', 'NotEmptyValidator', 'LANGUAGE_CAPTION_IS_EMPTY');
		$batch->add('date_format', 'DateFormatLengthValidator', 'TOO_LONG_DATE_FORMAT');
		$batch->add('date_format', 'DateFormatValidator', 'INVALID_DATE_FORMAT');
		$batch->add('decimal_separator', 'DecimalsSeparatorValidator', 'INVALID_DECIMALS_SEPARATOR');
		$batch->add('thousands_separator', 'ThousandsSeparatorValidator', 'INVALID_THOUSANDS_SEPARATOR');
		$batch->add('thousands_separator', 'DifferentThousandsAndDecimalSeparatorsValidator', 'THOUSANDS_AND_DECIMAL_SEPARATORS_MUST_BE_DIFFERENT');
		$batch->add('active', 'DefaultLanguageMustBeActiveValidator', 'DEFAULT_LANGUAGE_MUST_BE_ACTIVE');
		$batch->add('languageId', 'LanguageFileIsWritableValidator', 'LANGUAGE_FILE_IS_NOT_WRITABLE');

		return $batch;
	}
	
	function &createDeleteLanguageValidator($lang_id)
	{	
		$dataReflector = $this->reflectionFactory->createConstantReflector($lang_id);
		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->generalValidationFactory->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('', 'LanguageExistsValidator', 'LANGUAGE_NOT_EXISTS');
		$batch->add('', 'LanguageIsNotDefaultValidator', 'LANGUAGE_IS_DEFAULT');
		
		return $batch;
	}
	
	function &createSetDefaultLanguageValidator($lang_id)
	{	
		$dataReflector = $this->reflectionFactory->createConstantReflector($lang_id);
		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->generalValidationFactory->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('', 'LanguageExistsValidator', 'LANGUAGE_NOT_EXISTS');
		$batch->add('', 'LanguageIsActiveValidator', 'LANGUAGE_IS_NOT_ACTIVE');
		
		return $batch;
	}
	
	function &createImportLanguageValidator($lang_file_data)
	{	
		$dataReflector = $this->reflectionFactory->createHashtableReflector($lang_file_data);
		$this->setDataReflector($dataReflector);
		
		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->generalValidationFactory->createValidatorBatch($dataReflector, $factoryReflector);
				
		$batch->add('languageId', 'LanguageNotExistsValidator', 'LANGUAGE_ALREADY_EXISTS');
		$batch->add('lang_file_path', 'LanguageFileValidator', 'LANGUAGE_FILE_IS_INVALID');
		
		return $batch;
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
	function setDataReflector(&$dataReflector)
	{
		$this->dataReflector = $dataReflector;
	}

	
	function &createThousandsSeparatorValidator()
	{
		$formatValidator = $this->generalValidationFactory->createFormatValidator("/(.)/", $this->context->getValidThousandsSeparators());
		$lengthValidator = $this->generalValidationFactory->createMaxLengthValidator(1);		
		$andValidator = $this->generalValidationFactory->createAndValidator($formatValidator, $lengthValidator);
		return $andValidator;
	}
	
	function &createDecimalsSeparatorValidator()
	{
		$formatValidator = $this->generalValidationFactory->createFormatValidator("/(.)/", $this->context->getValidDecimalsSeparators());
		$lengthValidator = $this->generalValidationFactory->createMaxLengthValidator(1);		
		$andValidator = $this->generalValidationFactory->createAndValidator($formatValidator, $lengthValidator);
		return $andValidator;
	}
	
	function &createDateFormatValidator()
	{
		$validator = $this->generalValidationFactory->createFormatValidator("/%(.?)/", $this->context->getDateFormatValidSymbols());
		return $validator;
	}
	
	function &createDateFormatLengthValidator()
	{
		$validator = $this->generalValidationFactory->createMaxLengthValidator($this->context->getDateFormatMaxLength());
		return $validator;
	}
	
	function &createDecimalsValidator()
	{
		$validator = $this->generalValidationFactory->createRegexValidator("/^\d?$/");
		return $validator;
	}
	
	function &createLanguageCaptionLengthValidator()
	{
		$validator = $this->generalValidationFactory->createMaxLengthValidator($this->context->getLanguageCaptionMaxLength());
		return $validator;
	}
	
	function &createLanguageNotExistsValidator()
	{
		$source_validator = $this->createLanguageExistsValidator();
		$validator = $this->generalValidationFactory->createNotValidator($source_validator);
		return $validator;
	}
	
	function createLanguageExistsValidator()
	{
				$validator = new LanguageExistsValidator();
		$validator->setLanguageDataSource($this->langDataSource);
		return $validator;
	}
	
	function createLanguageIDLengthValidator()
	{
		$validator = $this->generalValidationFactory->createMaxLengthValidator($this->context->getLanguageIDMaxLength());
		return $validator;
	}

	function createNotEmptyValidator()
	{
		$validator = $this->generalValidationFactory->createNotEmptyValidator();
		return $validator;
	}
	
	function createIDSymbolsValidator()
	{
		$validator = $this->generalValidationFactory->createRegexValidator('/^[0-9a-zA-Z_]+$/');
		return $validator;
	}
	
	function createLanguageIsDefaultValidator()
	{
				$validator = new LanguageIsDefaultValidator();
		$validator->setContext($this->context);
		return $validator;	
	}
	
	function createLanguageIsNotDefaultValidator()
	{
		$source_validator = $this->createLanguageIsDefaultValidator();
		$validator = $this->generalValidationFactory->createNotValidator($source_validator);
		return $validator;
	}
	
	function createLanguageIsActiveValidator()
	{
				$validator = new LanguageIsActiveValidator();
		$validator->setLanguageDataSource($this->langDataSource);
		$validator->setLanguageExistsValidator($this->createLanguageExistsValidator());
		return $validator;
	}
	
	function createDefaultLanguageMustBeActiveValidator()
	{
				$validator = new DefaultLanguageMustBeActiveValidator();
		$validator->setDataReflector($this->dataReflector);
		$validator->setLanguageIsNotDefaultValidator($this->createLanguageIsNotDefaultValidator());
		return $validator;
	}
	
	function createDifferentThousandsAndDecimalSeparatorsValidator()
	{
		$equalToValidator = $this->generalValidationFactory->createEqualToValidator($this->dataReflector->get('decimal_separator'));
		$validator = $this->generalValidationFactory->createNotValidator($equalToValidator);
		return $validator;
	}
		
	function createLanguageFileValidator()
	{
		$validator = new LanguageFileValidator();
		$validator->setDataReflector($this->dataReflector);
		return $validator;	
	}

	public function createLanguageFileIsWritableValidator()
	{
		$validator = new LanguageFileIsWritableValidator();
		return $validator;
	}
	
}

?>

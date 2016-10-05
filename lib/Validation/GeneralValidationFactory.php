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

class GeneralValidationFactory
{
	function createValidatorBatch(&$dataReflector, &$factoryReflector)
	{
				$validator = new ValidatorBatch();
		$validator->setDataReflector($dataReflector);
		$validator->setValueValidatorFactoryReflector($factoryReflector);
		return $validator;
	}
	
	function createAndValidator()
	{
				$validator = new AndValidator();
		$validators = func_get_args();
		
		for ($i = 0; $i < count($validators); $i++)
		{
			$validator->add($validators[$i]);
		}
		
		return $validator;
	}
	
	function createFormatValidator($regex, $valid_symbols)
	{
				$validator = new FormatValidator();
		$validator->setRegex($regex);
		$validator->setValidSymbols($valid_symbols);
		return $validator;
	}
	
	function createMaxLengthValidator($max_length)
	{
				$validator = new MaxLengthValidator();
		$validator->setMaxLength($max_length);
		return $validator;
	}
	
	function createRegexValidator($regex)
	{
				$validator = new RegexValidator();
		$validator->setRegex($regex);
		return $validator;
	}
	
	function createNotValidator(&$source_validator)
	{
				$validator = new NotValidator();
		$validator->setValidator($source_validator);
		return $validator;
	}

	function createNotEmptyValidator()
	{
				$validator = new NotEmptyValidator();
		return $validator;
	}

	function createEqualToValidator($base_value)
	{
				$validator = new EqualToValidator();
		$validator->setBaseValue($base_value);
		return $validator;
	}
	
	function createDateValidator()
	{
				$validator = new DateValidator();
		return $validator;
	}
	
	function createIsoDateValidator()
	{
		$dateFormatter = \App()->ObjectMother->createDateFormatter("%Y-%m-%d");
		return $dateFormatter;
	}
	
	function createI18NDateValidator()
	{
		$dateFormatter = \App()->ObjectMother->createDateFormatter(\App()->I18N->getRawDateFormat());
		return $dateFormatter;
	}
	
	function createEmailValidator()
	{
				$validator = new EmailValidator();
		return $validator;
	}

	function createIntegerValidator()
	{
				$validator = new IntegerValidator();
		return $validator;
	}

	function createMoreThanValidator($baseValue)
	{
				$validator = new MoreThanValidator();
		$validator->setBaseValue($baseValue);
		return $validator;
	}

	function createMoreEqualValidator($baseValue)
	{
				$validator = new MoreEqualThanValidator();
		$validator->setBaseValue($baseValue);
		return $validator;
	}

	function createFloatValidator()
	{
				$validator = new FloatValidator();
		return $validator;
	}
	
	function createNaturalNumberValidator()
	{
		$integerValidator = $this->createIntegerValidator();
		$moreThanZeroValidator = $this->createMoreThanValidator(0);
		return $this->createAndValidator($integerValidator, $moreThanZeroValidator);
	}
}

?>

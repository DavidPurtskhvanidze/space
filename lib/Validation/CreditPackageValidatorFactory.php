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

class CreditPackageValidatorFactory
{
	var $generalValidationFactory;
	var $dataReflector;
	var $creditPackagesManager;
	
	function createAddCreditPackageValidator($data)
	{	
		$dataReflector = $this->reflectionFactory->createHashtableReflector($data);
		$this->setDataReflector($dataReflector);
		
		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->generalValidationFactory->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('name', 'NotEmptyValidator', 'NAME_IS_EMPTY');
		$batch->add('name', 'CreditPackageNameLengthValidator', 'TOO_LONG_NAME');
		$batch->add('credits_number', 'NotEmptyValidator', 'CREDITS_NUMBER_IS_EMPTY');
		$batch->add('credits_number', 'NaturalValidator', 'CREDITS_NUMBER_IS_NOT_NATURAL_NUMBER');
		$batch->add('price', 'NotEmptyValidator', 'PRICE_IS_EMPTY');
		$batch->add('price', 'PriceValidator', 'PRICE_IS_NOT_VALID');
		
		return $batch;
	}
	
	function createUpdateCreditPackageValidator($data)
	{	
		return $this->createAddCreditPackageValidator($data);
	}

	function createCreditPackageExistsValidator()
	{
				$validator = new CreditPackageExistsValidator();
		$validator->setCreditPackagesManager($this->creditPackagesManager);
		return $validator;
	}
	
	function createCreditPackageNameLengthValidator()
	{
		return $this->generalValidationFactory->createMaxLengthValidator(50);
	}
	
	function createNaturalValidator()
	{
		$integerValidator = $this->generalValidationFactory->createIntegerValidator();
		$moreThanZeroValidator = $this->generalValidationFactory->createMoreThanValidator(0);
		return $this->generalValidationFactory->createAndValidator($integerValidator, $moreThanZeroValidator);
	}
	
	function createPriceValidator()
	{
		$floatValidator = $this->generalValidationFactory->createFloatValidator();
		$moreOrEqualToZeroValidator = $this->generalValidationFactory->createMoreEqualValidator(0);
		return $this->generalValidationFactory->createAndValidator($floatValidator, $moreOrEqualToZeroValidator);
	}

	function createNotEmptyValidator()
	{
		$validator = $this->generalValidationFactory->createNotEmptyValidator();
		return $validator;
	}
	
	function setCreditPackagesManager($creditPackagesManager)
	{
		$this->creditPackagesManager = $creditPackagesManager;
	}
	
	function setDataReflector($dataReflector)
	{
		$this->dataReflector = $dataReflector;
	}
	
	function setReflectionFactory($reflectionFactory)
	{
		$this->reflectionFactory = $reflectionFactory;
	}
	
	function setGeneralValidationFactory($generalValidationFactory)
	{
		$this->generalValidationFactory = $generalValidationFactory;
	}
}

?>

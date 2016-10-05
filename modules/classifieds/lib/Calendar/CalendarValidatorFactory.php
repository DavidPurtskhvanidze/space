<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib\Calendar;

class CalendarValidatorFactory extends \lib\Validation\GeneralValidationFactory
{
	var $dataReflector;
	
	function &createAddPeriodValidator($period_data)
	{
		$batch = $this->createBasePeriodValidator($period_data);
		
		$batch->add('listing_sid', 'ListingAuthValidator', 'AUTHORIZATION_FAILED');
		
		return $batch;
	}

	function &createBookPeriodValidator($period_data)
	{
		$batch = $this->createBasePeriodValidator($period_data);
		
		$batch->add('sender_email', 'NotEmptyValidator', 'EMAIL_IS_EMPTY');
		$batch->add('sender_email', 'EmailValidator', 'EMAIL_NOT_VALID');
		$batch->add('sender_name', 'NotEmptyValidator', 'NAME_IS_EMPTY');
		
		return $batch;
	}

	function &createCheckArgumentsAction($period_data)
	{
		$dataReflector = $this->reflectionFactory->createHashtableReflector($period_data);
		$this->setDataReflector($dataReflector);

		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('listing_sid', 'NotEmptyValidator', 'LISTING_SID_IS_EMPTY');
		$batch->add('field_sid', 'NotEmptyValidator', 'FIELD_SID_IS_EMPTY');
		$batch->add('listing_sid', 'ListingAuthValidator', 'AUTHORIZATION_FAILED');
		
		return $batch;
	}

	function &createBasePeriodValidator($period_data)
	{
		$dataReflector = $this->reflectionFactory->createHashtableReflector($period_data);
		$this->setDataReflector($dataReflector);

		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('from', 'NotEmptyValidator', 'PERIOD_FROM_IS_EMPTY');
		$batch->add('to', 'NotEmptyValidator', 'PERIOD_TO_IS_EMPTY');
		$batch->add('from', 'I18NDateValidator', 'UNKNOWN_DATE_FORMAT_IN_PERIOD_FROM');
		$batch->add('to', 'I18NDateValidator', 'UNKNOWN_DATE_FORMAT_IN_PERIOD_TO');
		$batch->add('listing_sid', 'ListingExistsValidator', 'LISTING_NOT_FOUND');
		$batch->add('field_sid', 'FieldExistsValidator', 'FIELD_NOT_FOUND');
		$batch->add('', 'PeriodIntersectPeriodValidator', 'PERIODS_INTERSECTS');
		$batch->add('', 'FromBeforeToValidator', 'FROM_MUST_BE_BEFORE_TO');
		
		return $batch;
	}
	
	function &createDeletePeriodValidator($period_data)
	{	
		$dataReflector = $this->reflectionFactory->createHashtableReflector($period_data);
		$this->setDataReflector($dataReflector);
		$factoryReflector = $this->reflectionFactory->createFactoryReflector($this);
		
		$batch = $this->createValidatorBatch($dataReflector, $factoryReflector);
		
		$batch->add('listing_sid', 'ListingAuthValidator', 'DELETE_AUTHORIZATION_FAILED');
		$batch->add('periods', 'PeriodExistsValidator', 'PERIOD_NOT_EXISTS');
		
		return $batch;
	}
	
	function setReflectionFactory(&$reflectionFactory)
	{
		$this->reflectionFactory = $reflectionFactory;
	}
	
	function setDataReflector(&$dataReflector)
	{
		$this->dataReflector = $dataReflector;
	}

	function createPeriodExistsValidator()
	{
				$validator = new Validators\PeriodExistsValidator();
		$validator->setDB(\App()->DB);
		return $validator;
	}

	function createListingExistsValidator()
	{
				$validator = new Validators\ListingExistsValidator();
		$validator->setDB(\App()->DB);
		return $validator;
	}

	function createFieldExistsValidator()
	{
				$validator = new Validators\FieldExistsValidator();
		$validator->setReflector($this->dataReflector);
		$validator->setDB(\App()->DB);
		return $validator;
	}

	function createPeriodIntersectPeriodValidator()
	{
				$validator = new Validators\PeriodIntersectPeriodValidator();
		$validator->setReflector($this->dataReflector);
		$validator->setDB(\App()->DB);
		return $validator;
	}

	function createFromBeforeToValidator()
	{
				$validator = new Validators\FromBeforeToValidator();
		$validator->setReflector($this->dataReflector);
		return $validator;
	}

	function createListingAuthValidator()
	{
		$validator = new \modules\classifieds\lib\Actions\ListingAuthValidator();
        $admin = \App()->ObjectMother->createAdmin();
		$validator->setAdmin($admin);
		$validator->setListingManager(\App()->ListingManager);
		$validator->setUserManager(\App()->UserManager);
		return $validator;
	}
}
?>

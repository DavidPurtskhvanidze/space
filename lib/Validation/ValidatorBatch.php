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
class ValidatorBatch
{
	var $validationTable = array();

	function setDataReflector(&$reflector){
		$this->dataReflector =$reflector;
	}

	function setValueValidatorFactoryReflector(&$reflector){
		$this->valueValidatorFactoryReflector =$reflector;
	}

	function add($property_id, $validator_id, $error){
		$this->validationTable[$property_id][$validator_id] = $error;
	}

	function isValid(){
		$isValid = true;
		foreach($this->validationTable as $propertyId => $validators)
		{
			$value = $this->dataReflector->get($propertyId);
			foreach ($validators as $validatorId => $error)
			{
				$valueValidator = $this->valueValidatorFactoryReflector->create($validatorId);
				if (!$valueValidator->isValid($value))
				{
					\App()->ErrorMessages->addMessage($error);
					$isValid = false;
					break;
				}
			}
		}
		return $isValid;
	}
	
	function getErrors(){
		throw new \Exception('Please use {display_error_messages} instead of $errors');
	}
}

?>

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


namespace lib\Forms;

class Form
{
	private $formSubmitted = false;
	private $commonInputTemplateExists;
	private $commonInputTemplateName = "field_types^input/common.tpl";
	private $captchaFieldId = 'captcha';

	/**
	 * Data object
	 * @var \lib\ORM\Object
	 */
	protected $object;
	/**
	 * @var \lib\ORM\ObjectProperty[]
	 */
	var $object_properties	= array();
	var $path_to_templates  = 'field_types^';
	var $form_fields 		= array();

	/**
	 * @param \lib\ORM\Object $object
	 * @param array $fieldsOrder
	 * @param bool $captchaEnabled
	 */
	public function __construct($object = null, $fieldsOrder = array(), $captchaEnabled = false)
	{
		if (!empty($object))
		{
			if ($captchaEnabled)
			{
				$this->addCaptchaProperty($object);
			}

			$this->object = $object;
			$this->object_properties = $object->getProperties();

			// fields which is not defined in the $fieldsOrder will be added to the end of $fieldsOrder
			$fieldsOrder = array_unique(array_merge($fieldsOrder, array_keys($this->object_properties)));

			foreach ($fieldsOrder as $propertyId)
			{
				$this->form_fields[$propertyId] = $this->getFormFieldInfoForProperty($this->object_properties[$propertyId]);
			}
		}
	}

	/**
	 * @param \lib\ORM\Object $object
	 */
	private function addCaptchaProperty($object)
	{
		$captchaFieldData = array
		(
			'id' => $this->captchaFieldId,
			'caption' => 'Enter code from image',
			'type' => 'string',
			'is_required' => true,
			'value' => \App()->Request[$this->captchaFieldId],
			'save_into_db' => false,
			'input_template' => 'miscellaneous^captcha.tpl',
			'validators' => array(new \modules\miscellaneous\lib\CaptchaValidator()),
		);
		$object->addProperty($captchaFieldData);
	}

	/**
	 * @param \lib\ORM\ObjectProperty $object_property
	 * @return array
	 */
	private function getFormFieldInfoForProperty($object_property)
	{
		$form_field = array();
		$form_field['caption'] = $object_property->getCaption();
		$form_field['id'] = $object_property->getID();
		$form_field['sid'] = $object_property->getSID();
		$form_field['is_required'] = $object_property->isRequired();
		$form_field['disabled'] = false;
		$form_field['order'] = $object_property->getOrder();
		$form_field['type'] = $object_property->getType();
		$form_field['search_template'] = $this->getDefaultTemplateByFieldName($object_property->getID(),'search');
		return $form_field;
	}

	/**
	 * @param \modules\smarty_based_template_processor\lib\TemplateProcessor $template_processor
	 */
	public function registerTags($template_processor)
	{
		$this->commonInputTemplateExists = $template_processor->templateExists($this->commonInputTemplateName);

		$template_processor->registerPlugin("function", "input", array($this, "tpl_input"));
		$template_processor->registerPlugin("function", "display", array($this, "tpl_display"));
	}

	public function makeDisabled($property_id)
	{
		$this->form_fields[$property_id]['disabled'] 	 = true;
		$this->form_fields[$property_id]['is_required']  = false;
	}

	public function makeNotRequired($property_id)
	{
		$this->form_fields[$property_id]['is_required'] = false;
	}

	public function getFormFieldsInfo()
	{
		return $this->form_fields;
	}
	
	public function getFormFieldsIdsByType($type)
	{
		$formFields = array_filter($this->form_fields,
			function ($field) use ($type)
			{
				return $field['type'] == $type;
			});
		return array_keys($formFields);
	}

	private $notValidPropertyIds = array();

	function isDataValid($category_sid = null)
	{
		$this->notValidPropertyIds = array();
		$isValid = true;
		foreach ($this->object_properties as $object_property)
		{
			$objectPropertyHasError = false;
			if (!$object_property->isValid($category_sid))
			{
				$this->notValidPropertyIds[] = $object_property->getID();
				$objectPropertyHasError = true;
				$isValid = false;
			}
			$this->form_fields[$object_property->getID()]['has_error'] = $objectPropertyHasError;
		}
		if (!$isValid)
		{
			\App()->ErrorMessages->addMessage('VALIDATION_ERROR');
		}
		return $isValid;
	}

    /**
     * @param array $propertiesToCheck
     * @return bool
     */
	public function isDataValidPartially($propertiesToCheck)
	{
		$isValid = true;
        foreach ($propertiesToCheck as $propertyId)
        {
            if ($this->objectHasProperty($propertyId))
            {
                $isValid &= $this->getObjectProperty($propertyId)->isValid();
			}
		}
		if (!$isValid)
		{
			\App()->ErrorMessages->addMessage('VALIDATION_ERROR');
		}
		return $isValid;
	}

	private function objectHasProperty($property_name)
	{
		return isset($this->object_properties[$property_name]) ? true : $this->object->propertyIsSet($property_name);
	}

	private function getObjectProperty($property_name)
	{
		return  isset($this->object_properties[$property_name]) ? $this->object_properties[$property_name] : $this->object->getProperty($property_name);
	}

    protected $tokenRendered = false;

    protected function renderToken()
    {
        if (strpos(\App()->Navigator->getURI(), 'api') !== false) {
            return false;
        }
        $hidden = '';
        if (!$this->tokenRendered)
        {
            $hidden = '<input type="hidden" name="secure_token" value="' . \App()->Token->getToken() . '">';
            $this->tokenRendered = true;
        }

        return $hidden;
    }

	/**
	 * @param $view_type
	 * @param $params
	 * @param \modules\smarty_based_template_processor\lib\TemplateProcessor $templateProcessor
	 * @return mixed
	 */
	public function tpl_property($view_type, $params, $templateProcessor)
	{
		if (!$this->objectHasProperty($params['property']))
		{
			return $this->unknownPropertyRequestedError($params, $templateProcessor);
		}

		$template = isset($params['template']) ? $params['template'] : $this->getDefaultTemplateByFieldName($params['property'], $view_type);
		if (false !== strpos($template, '^'))
		{
			$template_path = $template;
		}
		else {
			$template_path = $this->path_to_templates . $view_type . '/' . $template;
		}

		/**
		 * @var \Smarty_Internal_Template
		 */
		$smartyTemplate = $templateProcessor->createTemplate($template_path, $templateProcessor);
		$this->assignTemplateVariables($params, $smartyTemplate, $templateProcessor);

		return $smartyTemplate->fetch();
	}

	/**
	 * @param $params
	 * @param \modules\smarty_based_template_processor\lib\TemplateProcessor $templateProcessor
	 * @return mixed
	 */
	public function unknownPropertyRequestedError($params, $templateProcessor)
	{
		$template_path = $this->path_to_templates . 'errors.tpl';
		$templateProcessor->assign($params);
		return $templateProcessor->fetch($template_path);
	}

	/**
	 * @param $params
	 * @param \Smarty_Internal_Template $smartyTemplate
	 * @param \modules\smarty_based_template_processor\lib\TemplateProcessor $templateProcessor
	 */
	function assignTemplateVariables($params, $smartyTemplate, $templateProcessor)
	{
		$object_property = $this->getObjectProperty($params['property']);
		$variables_to_assign = $object_property->getPropertyVariablesToAssign();
		$type = $object_property->getType();
		if (isset($params['parameters'])) $variables_to_assign['parameters'] = $params['parameters'];
		$variables_to_assign['placeholder'] = isset($params['placeholder']) ? $params['placeholder'] : null;

		$variables_to_assign = array_merge($variables_to_assign, $this->getVariablesToAssign($params));

		if (!$this->formSubmitted && empty($variables_to_assign['value']) && !empty($params['default']))
		{
			$variables_to_assign['value'] = $params['default'];
		}

		foreach ($variables_to_assign as $variable_name => $variable_value)
		{
			if(in_array($type, array('string', 'text', 'list')) && (!isset($params['skip_escaping']) || $params['skip_escaping'] != true))
			{
				$smartyTemplate->assign($variable_name, $templateProcessor->filterValueToAssign($variable_value));
			}
			else
			{
				$smartyTemplate->assign($variable_name, $variable_value);
			}
		}
		$smartyTemplate->assign('vars', array_keys($variables_to_assign));
		$smartyTemplate->assign('property_data', \App()->AutocompleteManager->packPropertyForRequest($object_property));

		// the remaining parameters assigned as is
		$propertiesToExclude = array('property', 'parameters', 'default', 'skip_escaping');
		foreach ($propertiesToExclude as $propertyToExclude)
		{
			unset($params[$propertyToExclude]);
		}
		$smartyTemplate->assign($params);
	}

	function getVariablesToAssign($params)
	{
		return array();
	}

	/**
	 * @param $params
	 * @param \modules\smarty_based_template_processor\lib\TemplateProcessor $templateProcessor
	 * @return mixed
	 */
	function tpl_input($params, $templateProcessor)
	{
		$result = "";
		if ($this->commonInputTemplateExists)
		{
			$result .= $templateProcessor->fetch($this->commonInputTemplateName);
		}

		if ($this->form_fields[$params['property']]['disabled'])
		{
			$result .= $this->tpl_property('display', $params, $templateProcessor);
		}
		else
		{
			$result .= $this->tpl_property('input', $params, $templateProcessor);
            $result .= $this->renderToken();
		}
		return $result;
	}

	function tpl_search($params, $templateProcessor)
	{
		if ($this->form_fields[$params['property']]['disabled'])
		{
			return $this->tpl_property('display', $params, $templateProcessor);
		}
		else
		{
			return $this->tpl_property('search', $params, $templateProcessor) . $this->renderToken();
		}
	}

	/**
	 * @param $params
	 * @param \modules\smarty_based_template_processor\lib\TemplateProcessor $templateProcessor
	 * @return string
	 */
	function tpl_display($params, $templateProcessor)
	{
    	if (isset($params['assign']))
    	{
			$templateProcessor->filterThenAssign($params['assign'], trim($this->tpl_property('display', $params, $templateProcessor)));
		}
		else
		{
			return trim($this->tpl_property('display', $params, $templateProcessor));
		}
	}

	function getDefaultTemplateByFieldName($property_name, $view_type)
	{
		if ($this->objectHasProperty($property_name))
		{
			$template_name = isset($this->getObjectProperty($property_name)->type->property_info[$view_type . '_template'])
				? $this->getObjectProperty($property_name)->type->property_info[$view_type . '_template']
				: $this->getObjectProperty($property_name)->getDefaultTemplate()
			;
			if(empty($template_name))
			{
				$property_type = $this->object_properties[$property_name]->getType();
				$template_name = $property_type . '.tpl';
			}
		}
		else
		{
			$template_name = 'string.tpl';
		}

		return $template_name;
	}

	public function getNotValidPropertyIds()
	{
		return $this->notValidPropertyIds;
	}

	/**
	 * @return \lib\ORM\Object
	 */
	public function getObject()
	{
		return $this->object;
	}

	public function setFormSubmitted($formSubmitted)
	{
		$this->formSubmitted = $formSubmitted;
	}
}

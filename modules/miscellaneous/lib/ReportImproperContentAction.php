<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class ReportImproperContentAction
{
	var $errors = array();
	var $form;
	var $formObject;
	var $template_processor;
	var $formSubmitted = false;

	private $objectType;
	private $objectId;
	/**
	 * @var IReportImproperContentObjectType[]
	 */
	private $supportedObjectTypes = array();

	public function __construct()
	{
		/**
		 * @var IReportImproperContentObjectType[] $objectTypes
		 */
		$objectTypes = new \core\ExtensionPoint('modules\miscellaneous\lib\IReportImproperContentObjectType');
		foreach ($objectTypes as $objectType)
		{
			$this->supportedObjectTypes[$objectType->getType()] = $objectType;
		}
	}
	
	public function setObjectType($objectType)
	{
		$this->objectType = $objectType;
	}
	public function setObjectId($objectId)
	{
		$this->objectId = $objectId;
	}

    private $returnBackUri = null;
    public function setReturnBackUri($returnBackUri)
    {
        $this->returnBackUri = $returnBackUri;
    }

	function canPerform()
	{
		if (!array_key_exists($this->objectType, $this->supportedObjectTypes))
		{
			$this->errors[] = 'NOT_SUPPORTED_OBJECT_TYPE';
		}
		elseif (!$this->supportedObjectTypes[$this->objectType]->doesObjectExist($this->objectId))
		{
			$this->errors[] = strtoupper($this->objectType) . '_DOES_NOT_EXIST';
		}
		return empty($this->errors);
	}

	function perform()
	{
		$errors = array();
		if ($this->formSubmitted && $this->form->isDataValid())
		{
			$message_sent = $this->sendReport();
			if (!$message_sent)
			{
				$errors[] = 'CANNOT_SEND_MAIL';
			}
            elseif (!empty($this->returnBackUri))
            {
				\App()->SuccessMessages->addMessage('BAD_CONTENT_REPORT_SENT');
                throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . $this->returnBackUri);
            }
			$this->template_processor->assign('message_sent', $message_sent);
			$this->template_processor->assign('errors', $errors);
		}
		else
		{
			$form_fields = $this->form->getFormFieldsInfo();
			$this->template_processor->assign("form_fields", $form_fields);
		}
		$this->template_processor->assign('objectType', $this->objectType);
		$this->template_processor->assign('objectId', $this->objectId);
		$this->template_processor->assign('returnBackUri', $this->returnBackUri);
		$this->template_processor->display('report_improper_content.tpl');
	}
	
	private function sendReport()
	{
		$messageTemplate = $this->supportedObjectTypes[$this->objectType]->getMessageTemplateName();
		$parameters = $this->supportedObjectTypes[$this->objectType]->getMessageParameters($this->objectId);
		return $this->sendImproperContentMessage($messageTemplate, $this->formObject, $parameters);
	}

	private function sendImproperContentMessage($messageTemplate, $formDataObject, $parameters)
	{
		$parameters['formData'] = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($formDataObject);
		$parameters['admin_site_url'] = \App()->SystemSettings->getSettingForApp('AdminPanel', 'SiteUrl');
		return \App()->EmailService->sendToAdmin($messageTemplate, $parameters);
	}

	function setFormObject($formObject)
	{
		$this->formObject = $formObject;
	}
	
	function setForm($form)
	{
		$this->form = $form;
	}
	
	function setTemplateProcessor($template_processor)
	{
		$this->template_processor = $template_processor;
	}
	
	function setFormSubmitted($formSubmitted)
	{
		$this->formSubmitted = $formSubmitted;
	}
	
	function getErrors()
	{
		return $this->errors;
	}
}

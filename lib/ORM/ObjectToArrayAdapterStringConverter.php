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


namespace lib\ORM;

class ObjectToArrayAdapterStringConverter
{
    private $templateProcessor;
    private $objectToArrayAdapter;

    public function getConverted($objectToArrayAdapter)
	{
		$this->setObjectToArrayAdapter($objectToArrayAdapter);
        $object = $objectToArrayAdapter->getObject();
        $objectGroup = strtolower(substr(strrchr(get_class($object), '\\'), 1));
        $this->templateProcessor->assign($objectGroup, $objectToArrayAdapter);
        return $this->templateProcessor->fetch("string:" .  $object->getTemplateContentForStringRepresentation(), null, 'orm');
	}

	public function setTemplateProcessor($templateProcessor)
	{
		$this->templateProcessor = $templateProcessor;
	}

	public function setObjectToArrayAdapter($objectToArrayAdapter)
	{
		$this->objectToArrayAdapter = $objectToArrayAdapter;
	}
}

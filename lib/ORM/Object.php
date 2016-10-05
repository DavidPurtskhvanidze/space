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

class Object
{
    use BelongsTo;
    use HasMany;

    var $sid;
    /**
     * @var ObjectDetails
     */
    var $details;
    var $errors = array();

    function &getDetails()
    {
        return $this->details;
    }

    function setDetails($dt)
    {
        $this->details = $dt;
        $this->details->setObject($this);
    }

    /**
     * @return ObjectProperty[]
     */
    function getProperties()
    {
        return $this->details->getProperties();
    }

    function setSID($sid)
    {
        $this->details->setObjectSID($sid);
        $this->sid = $sid;
    }

    function getSID()
    {
        return $this->sid;
    }

    function getID()
    {
        $id = $this->getPropertyValue('id');

        if (!empty($id)) return $id;
        else                return $this->sid;
    }

    function addProperty($property_info)
    {
        $this->details->addProperty($property_info);
    }

    function deleteProperty($property_id)
    {
        $this->details->deleteProperty($property_id);
    }

    public function deletePropertiesByTypes($types)
    {
        foreach ($this->getProperties() as $property) {
            if (in_array($property->getType(), $types)) {
                $this->deleteProperty($property->getID());
            }
        }

    }

    function getProperty($property_id)
    {
        return $this->details->getProperty($property_id);
    }

    function makePropertyNotRequired($property_id)
    {
        return $this->details->makePropertyNotRequired($property_id);
    }

    function dontSaveProperty($property_id)
    {
        return $this->details->dontSaveProperty($property_id);
    }

    function propertyIsSet($property_id)
    {
        return $this->details->propertyIsSet($property_id);
    }

    function getPropertyDisplayValue($property_id)
    {
        $property = $this->details->getProperty($property_id);
        return  !empty($property) ? $property->getDisplayValue() : null;
    }

    function getPropertyExportValue($property_id)
    {
        $property = $this->details->getProperty($property_id);
        return !empty($property)? $property->getExportValue() : null;
    }

    /**
     * @param string $property_id
     * @return mixed
     */
    function getPropertyValue($property_id)
    {
        $property = $this->details->getProperty($property_id);
        return !empty($property) ? $property->getValue(): null;
    }

    function setPropertyValue($property_id, $value)
    {
        $property = $this->details->getProperty($property_id);
        return !empty($property)? $property->setValue($value): false;
    }

    function getErrors()
    {
        return $this->errors;
    }

    function toArray($decorator = null)
    {
        $res = array();
        $properties = $this->getProperties();
        foreach (array_keys($properties) as $key) {
            $property = $properties[$key];
            $value = $property->getValue();
            if (!is_null($decorator) && in_array($property->getType(), array('string', 'text', 'list'))) {
                $value = call_user_func_array($decorator, array(&$value));
            }
            $res[$property->getId()] = $value;

            if ($property->getType() == 'geo' || $property->getType() == 'rating') // geodata for google Maps
            {
                $res[$property->getID() . '_Data'] = $property->getPropertyVariablesToAssign();
            }
        }
        $res['sid'] = $this->getSid();
        return $res;
    }

    function incorporateData($data)
    {
        $this->details->incorporateData($data);
    }

    public function getTableName()
    {
        return $this->details->getTableName();
    }

    public function getTableAlias()
    {
        return $this->details->getTableAlias();
    }

    private $templateContentForStringRepresentation;

    public function setTemplateContentForStringRepresentation($content)
    {
        $this->templateContentForStringRepresentation = $content;
    }

    public function getTemplateContentForStringRepresentation()
    {
        return $this->templateContentForStringRepresentation;
    }

    private $templateLastModifiedForStringRepresentation;

    public function setTemplateLastModifiedForStringRepresentation($templateLastModifiedForStringRepresentation)
    {
        $this->templateLastModifiedForStringRepresentation = $templateLastModifiedForStringRepresentation;
    }

    public function getTemplateLastModifiedForStringRepresentation()
    {
        return $this->templateLastModifiedForStringRepresentation;
    }

    private $templateIdForStringRepresentation;

    public function setTemplateIdForStringRepresentation($templateIdForStringRepresentation)
    {
        $this->templateIdForStringRepresentation = $templateIdForStringRepresentation;
    }

    public function getTemplateIdForStringRepresentation()
    {
        return $this->templateIdForStringRepresentation;
    }

    private $templateIdForUrlSeoData;

    public function setTemplateIdForUrlSeoData($templateIdForUrlSeoData)
    {
        $this->templateIdForUrlSeoData = $templateIdForUrlSeoData;
    }

    public function getTemplateIdForUrlSeoData()
    {
        return $this->templateIdForUrlSeoData;
    }

    private $templateContentForUrlSeoData;

    public function setTemplateContentForUrlSeoData($templateContentForUrlSeoData)
    {
        $this->templateContentForUrlSeoData = $templateContentForUrlSeoData;
    }

    public function getTemplateContentForUrlSeoData()
    {
        return $this->templateContentForUrlSeoData;
    }

    private $templateLastModifiedForUrlSeoData;

    public function setTemplateLastModifiedForUrlSeoData($templateLastModifiedForUrlSeoData)
    {
        $this->templateLastModifiedForUrlSeoData = $templateLastModifiedForUrlSeoData;
    }

    public function getTemplateLastModifiedForUrlSeoData()
    {
        return $this->templateLastModifiedForUrlSeoData;
    }

    public function defineRefineSearchExtraDetailsAttributes()
    {
        $properties = $this->getProperties();
        $typesToUpdate = array('integer', 'float', 'decimal', 'money');
        foreach ($properties as $id => $property) {
            if (in_array($property->getType(), $typesToUpdate) !== false)
                $property->defineRefineSearchExtraDetailsAttributes();
        }
    }

    public function getValueForEncodingToJson()
    {
        $res = array();
        $res['sid'] = array('caption' => 'SID', 'value' => $this->getSID());
        foreach ($this->getProperties() as $propertyId => $property) {
            if ($property->getType() != 'password') {
                $res[$propertyId] = array
                (
                    'caption' => $property->getCaption(),
                    'value' => $property->getValueForEncodingToJson(),
                );
            }
        }
        return $res;
    }
}

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


namespace modules\classifieds\apps\AdminPanel\scripts;

class CategorySettingsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'category_settings';

	public function respond()
	{
		$category_sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;
		
		if (!is_null($category_sid))
		{
		    $category_info = \App()->CategoryManager->getInfoBySID($category_sid);
		    $category_info = array_merge($category_info, $_REQUEST);
		    $category = \App()->CategoryManager->getCategory($category_info);
		    $category->setSID($category_sid);
            if ($category_sid == 0)
                $category = \App()->CategoryManager->alterPropertiesForRootCategoryNode($category);
            $edit_form = $this->makeForm($category);
            $saveForm = ($this->_getReqVal('action', 'none') == 'save_info');

            $details_properties = $category->getDetails()->getProperties();
            $details_properties['browsing_settings']->type->property_info['list_values'] = $this->_makeBrowingOptions($category_sid);

            if ($this->_getReqVal('handler_add_browsing_setting', false))
            {
                $this->_addBrowingSettings($details_properties['browsing_settings']->type);
                $saveForm = true;
            }
            else if ($this->_getReqVal('handler_move_browsing_setting', false)) {
                $this->_moveBrowingSettings(
                    $details_properties['browsing_settings']->type,
                    $this->_getReqVal('handler_move_browsing_setting'),
                    $this->_getReqVal('dir', 'down')
                );
                $saveForm = true;
            }
            else if ($this->_getReqVal('handler_delete_browsing_setting', false))
            {
                $this->_deleteBrowingSettings(
                    $details_properties['browsing_settings']->type,
                    $this->_getReqVal('handler_delete_browsing_setting')
                );
                $saveForm = true;
            }

		    if ($saveForm && $edit_form->isDataValid())
		    {
				$canPerform = true;
				$validators = new \core\ExtensionPoint('modules\classifieds\apps\AdminPanel\IEditCategorySettingValidator');
				foreach ($validators as $validator)
				{
					$validator->setCategory($category);
					$canPerform &= $validator->isValid();
				}
				if ($canPerform)
					\App()->CategoryManager->saveCategory($category);
                if ($this->_getReqVal('handler_save_category', false))
                    throw new \lib\Http\RedirectException( \App()->PageRoute->getPagePathById('edit_category') . '?sid=' . $category->getSID() );
                else
    		        throw new \lib\Http\RedirectException( \App()->PageRoute->getSystemPagePath($this->moduleName, 'category_settings') . "?sid=" . $category->getSID() );
            }
		    else
		    {
				$template_processor = \App()->getTemplateProcessor();
		        $edit_form->registerTags($template_processor);
		        $template_processor->assign("tree_fields_ids", $this->getCategoryTreeFieldsIds($category_sid));
		        $template_processor->assign("category_sid", $category_sid);
		        $template_processor->assign("category", $category_info);
		        $template_processor->assign("form_fields", $edit_form->getFormFieldsInfo());
		        $template_processor->assign("ancestors", array_reverse(\App()->CategoryTree->getAncestorsInfo($category_sid)));
		        $template_processor->assign("available_browsing_settings", \App()->ListingFieldManager->getListingFieldsInfoByCategory($category_sid));

		        $template_processor->display("edit_category.tpl");
		    }
		}
	}
	
	private function getCategoryTreeFieldsIds($categorySid)
	{
		$fields = \App()->ListingFieldManager->getListingFieldsInfoByCategory($categorySid);
		$fields = array_filter($fields, array($this, 'isTreeField'));
		$fields = array_map(array($this, 'getFieldId'), $fields);
		return $fields;
	}
	private function isTreeField($fieldInfo)
	{
		return $fieldInfo['type'] == 'tree';
	}
	private function getFieldId($fieldInfo)
	{
		return $fieldInfo['id'];
	}

    private function _makeBrowingOptions($CategorySid)
    {
        $options = array();

        $fields = \App()->ListingFieldManager->getListingFieldsInfoByCategory($CategorySid);
        $fields = array_filter($fields, array($this, 'isBrowsableField'));
        
        foreach ($fields as $field)
            $options[($field['id'])] = $field['id'];

        $options['category_sid'] = 'category_sid';

        return $options;
    }
    private function isBrowsableField($fieldInfo)
    {
    	return in_array($fieldInfo['type'], array('tree', 'list', 'integer', 'string', 'geo'));
	}

    private function _addBrowingSettings($field)
    {
    	$newSettings = $this->_getReqVal('new_browsing_setting', array());
        if (!is_array($newSettings) || empty($newSettings))
            return;
        $currSettings = $field->getValue();
        foreach($newSettings as $setting)
        {
        	$currSettings[] = $setting;
        }
        $field->setValue($currSettings);
    }

    private function _moveBrowingSettings($field, $value, $dir) {
        $currSettings = array_values($field->getValue());
        foreach($currSettings as $key => $setting)
        {
            if ($setting == $value)
            {
                $newKey = false;
                if ($dir === 'up' && $key > 0)
                    $newKey = $key - 1;
                else if ($dir === 'down' && ($key < count($currSettings) - 1))
                    $newKey = $key + 1;

                if ($new !== false) {
                    $currSettings[$key] = $currSettings[$newKey];
                    $currSettings[$newKey] = $setting;

                    $field->setValue($currSettings);
                }
                
                return;
            }
        }
    }

    private function _deleteBrowingSettings($field, $value) {
        $currSettings = $field->getValue();
        foreach($currSettings as $key => $setting)
        {
            if ($setting == $value)
            {
                unset($currSettings[$key]);
                $field->setValue(array_values($currSettings));
                return;
            }
        }
    }

    private function _getReqVal($valueName, $defaultValue = null)
    {
        return (isset($_REQUEST[$valueName])) ? $_REQUEST[$valueName] : $defaultValue;
    }

    private function makeForm($category)
    {
	    $edit_form = new \lib\Forms\Form($category);
        if ($category->getSID() == 0)
        {
            $edit_form->makeDisabled('id');
        }

        return $edit_form;
    }
}

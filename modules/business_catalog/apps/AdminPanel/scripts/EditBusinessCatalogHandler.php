<?php
/**
 *
 *    Module: business_catalog v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: business_catalog-7.5.0-1
 *    Tag: tags/7.5.0-1@19772, 2016-06-17 13:18:58
 *
 *    This file is part of the 'business_catalog' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\business_catalog\apps\AdminPanel\scripts;

class EditBusinessCatalogHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\content_management\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'business_catalog';
	protected $functionName = 'edit_business_catalog';

	private $businessCatalog;

	public function respond()
	{
		$this->businessCatalog = new \modules\business_catalog\lib\BusinessCatalog();
		$categoryName = \App()->Request['category_name'];
		$categoryId = \App()->Request['category_id'];

		if (\App()->Request['action'] == 'createcategory')
		{
			if ($this->isValidNameID($categoryId, $categoryName))
			{
				if ($this->businessCatalog->bcategory_exists($categoryId) )
				{
					\App()->ErrorMessages->addMessage('NOT_UNIQUE_VALUE', array('fieldCaption' => 'ID'));
					$categoryId = null;
				}
				else
				{
					$this->businessCatalog->bcreate_category($categoryName, $categoryId);
				}
			}
			else
			{
				$categoryId = null;
			}
		}

		if (\App()->Request['action']=='deletecategory')
		{
			if (!is_null(\App()->Request['del_category_id']))
			{
				$canPerform = true;
				$validators = new \core\ExtensionPoint('modules\business_catalog\apps\AdminPanel\IDeleteBusinessCatalogCategoryValidator');
				foreach ($validators as $validator)
				{
					$validator->setId(\App()->Request['del_category_id']);
					$canPerform &= $validator->isValid();
				}
				if ($canPerform)
				{
					if (!$this->businessCatalog->bdelete_category($_REQUEST['del_category_id']))
					{
						\App()->ErrorMessages->addMessage('CANNOT_DELETE_CATEGORY');
					}
				}
			}
		}

		if (\App()->Request['action']=='edit_category')
		{
			if ($this->isValidNameID(\App()->Request['new_category_id'], \App()->Request['name']))
			{
				if ($this->businessCatalog->bcategory_exists(\App()->Request['new_category_id']))
				{
					\App()->ErrorMessages->addMessage('NOT_UNIQUE_VALUE', array('fieldCaption' => 'ID'));
				}
				elseif (!$this->businessCatalog->bedit_category($categoryId, \App()->Request['name'], \App()->Request['new_category_id']))
				{
					\App()->ErrorMessages->addMessage('CANNOT_MODIFY_CATEGORY');
				}
			}
		}

		if (\App()->Request['action']=='create_record')
		{
			if (isset(\App()->Request['record_name'], $categoryId))
			{
				if (!$this->businessCatalog->bcreate_record($categoryId, \App()->Request['record_name'], "Brief Description", "Address", "Phone", "Fax", "E-mail", "Website", ''))
				{
					\App()->ErrorMessages->addMessage('CANNOT_ADD_RECORD');
				}
			}
		}

		if (\App()->Request['action']=='edit_record')
		{
			if (isset($_REQUEST['record_id'],$_REQUEST['category_id'],$_REQUEST['name'],$_REQUEST['description'],$_REQUEST['address'],$_REQUEST['phone'],$_REQUEST['fax'],$_REQUEST['email'],$_REQUEST['url'],$_REQUEST['full']))
			{
				if (!$this->businessCatalog->bedit_record($_REQUEST['record_id'],$_REQUEST['category_id'],$_REQUEST['name'],$_REQUEST['description'],$_REQUEST['address'],$_REQUEST['phone'],$_REQUEST['fax'],$_REQUEST['email'],$_REQUEST['url'],$_REQUEST['full']))
				{
					\App()->ErrorMessages->addMessage('CANNOT_MODIFY_RECORD');
				}
				else
				{
					unset($_REQUEST['record_id']);
				}
			}
		}

		if (\App()->Request['action']=='deleterecord')
		{
			if (isset(\App()->Request['del_record_id']))
			{
				$canPerform = true;
				$validators = new \core\ExtensionPoint('modules\business_catalog\apps\AdminPanel\IDeleteBusinessCatalogRecordValidator');
				foreach ($validators as $validator)
				{
					$validator->setId(\App()->Request['del_record_id']);
					$canPerform &= $validator->isValid();
				}
				if ($canPerform)
				{
					if (!$this->businessCatalog->bdelete_record(\App()->Request['del_record_id']))
					{
						\App()->ErrorMessages->addMessage('CANNOT_DELETE_RECORD');
					}
				}
			}
		}

		if (isset($categoryId) && $this->businessCatalog->bcategory_exists($categoryId))
		{
			if (isset(\App()->Request['record_id']) && $this->businessCatalog->brecord_exists($categoryId, \App()->Request['record_id']))
			{
				$this->displayEditCategoryRecord($categoryId, \App()->Request['record_id']);
			}
			else
			{
				$this->displayEditCategory($categoryId);
			}
		}
		else
		{
			$this->displayCategories();
		}
	}

	private function displayEditCategoryRecord($categoryId, $recordId)
	{
		$category = $this->businessCatalog->bget_category($categoryId);
		$record = $this->businessCatalog->bget_record($recordId);
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("category", $category);
		$template_processor->assign("record", $record);
		$template_processor->assign('categories', $this->businessCatalog->bget_categories());
		$template_processor->display('edit_business_catalog_record.tpl');
	}

	private function displayEditCategory($categoryId)
	{
		$category = $this->businessCatalog->bget_category($categoryId);
		$pageInfo = array
		(
			'module' => 'business_catalog',
			'function' => 'show_business_catalog',
			'parameters' => array('category_id' => $categoryId),
		);
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('createPageForThisCategoryLink', \App()->ModuleManager->executeFunction('site_pages', 'register_page_link', array('pageInfo' => $pageInfo, 'caption' => 'category')));
		$template_processor->assign('category', $category);
		$template_processor->assign('category_id', $categoryId);
		$template_processor->assign('records', $this->businessCatalog->bget_records($categoryId, \App()->Request['sortingField'], \App()->Request['sortingOrder']));
		$template_processor->display('edit_business_catalog_category.tpl');
	}

	private function displayCategories()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('categories', $this->businessCatalog->bget_categories(\App()->Request['sortingField'], \App()->Request['sortingOrder']));
		$template_processor->display('business_catalog_categories.tpl');
	}

	private function isValidNameID($id, $name)
	{
		if (empty($name))
		{
			\App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => 'Name'));
		}

		if (empty($id))
		{
			\App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => 'ID'));
		}
		elseif (!preg_match("(^\w+$)", $id))
		{
			\App()->ErrorMessages->addMessage('NOT_VALID_ID_VALUE', array('fieldCaption' => 'ID'));
		}

		return \App()->ErrorMessages->isEmpty();
	}

	public function getCaption()
	{
		return "Business Catalog";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('business');
	}

	public function getHighlightUrls()
	{
		return array();
	}

	public static function getOrder()
	{
		return 100;
	}
}

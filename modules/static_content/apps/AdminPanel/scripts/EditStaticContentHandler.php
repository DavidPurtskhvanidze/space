<?php
/**
 *
 *    Module: static_content v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: static_content-7.5.0-1
 *    Tag: tags/7.5.0-1@19836, 2016-06-17 13:22:00
 *
 *    This file is part of the 'static_content' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\static_content\apps\AdminPanel\scripts;

class EditStaticContentHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\content_management\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'static_content';
	protected $functionName = 'edit_static_content';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$staticContent = new \modules\static_content\lib\StaticContent();

		$action = \App()->Request['action'];
		$id = \App()->Request['pageid'];
		$newId = \App()->Request['new_pageid'];
		$name = \App()->Request['name'];

		if ($action == 'add')
		{
			if ($this->isValidNameID($id, $name))
			{
				if (!$staticContent->getStaticContent($id))
				{
					$contentInfo = array('id' => $id, 'name' => $name,);
					if ($staticContent->addStaticContent($contentInfo))
					{
						throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI());
					}
					else
					{
						\App()->ErrorMessages->addMessage('CANNOT_ADD_PAGE');
					}
				}
				else
				{
					\App()->ErrorMessages->addMessage('NOT_UNIQUE_VALUE', array('fieldCaption' => 'ID'));
				}
			}
		}

		if ($action == 'change')
		{
			if ($this->isValidNameID($newId, $name))
			{
				if ($id == $newId || !$staticContent->getStaticContent($newId))
				{
					$contentInfo = array
					(
						'id' => $id,
						'name' => $name,
						'content' => \App()->Request['content']
					);
					if ($staticContent->changeStaticContent($contentInfo, $newId))
					{
						throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI());
					}
					else
					{
						\App()->ErrorMessages->addMessage('CANNOT_UPDATE_PAGE');
					}
				}
				else
				{
					\App()->ErrorMessages->addMessage('NOT_UNIQUE_VALUE', array('fieldCaption' => 'ID'));
				}
			}
			$action = 'edit';
		}

		if ($action == 'delete')
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\static_content\apps\AdminPanel\IDeleteStaticContentValidator');
			foreach ($validators as $validator)
			{
				$validator->setPageId($id);
				$canPerform &= $validator->isValid();
			}

			if ($canPerform)
			{
				$staticContent->deleteStaticContent($id);
			}
			else
			{
				throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI());
			}
		}

		if ($action == 'edit')
		{
			$page = $staticContent->getStaticContent($id);

			$templateProcessor->assign("page", array_map("htmlspecialchars", $page));
			$result = $templateProcessor->fetch("header_static_content.tpl");

			$pageInfo = array
			(
				'module' => 'static_content',
				'function' => 'show_static_content',
				'parameters' => array('pageid' => $id),
			);
			$result .= \App()->ModuleManager->executeFunction('site_pages', 'register_page_link', array('pageInfo' => $pageInfo, 'caption' => 'static content article'));

			$templateProcessor->assign("page_content", $page["content"]);
			$templateProcessor->assign("pageid", $id);
			$result .= $templateProcessor->fetch("static_content_change.tpl");
			echo $result;
			return;
		}

		$templateProcessor->assign("pages", $staticContent->getStaticContents(\App()->Request['sortingField'], \App()->Request['sortingOrder']));
		$templateProcessor->display("static_content.tpl");
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
		return "Static Content";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('stat_pages');
	}

	public function getHighlightUrls()
	{
		return array();
	}

	public static function getOrder()
	{
		return 400;
	}
}

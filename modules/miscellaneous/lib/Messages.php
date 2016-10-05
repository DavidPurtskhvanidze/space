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

abstract class Messages implements \core\IService
{
	/**
	 * @var \core\SessionContainer
	 */
	private $dataSource;

	public function init()
	{
		$this->dataSource = \App()->Session->getContainer("MESSAGES");
	}

	abstract public function addMessage($id, $data = array());
	abstract public function fetchMessages();

	protected  function fetchMessagesByType($type)
	{
		$templateProcessor = \App()->TemplateProcessor->getFreshInstance();
		$templateProcessor->setIfAddTemplateStartEndComments(!(bool) \App()->Request->getValueOrDefault('skip_template_comments', false));

		$messages = $this->getMessages($type);
		$renderedMessages = array();
		if (!is_null($messages))
		{
			foreach ($messages as $message)
			{
				foreach ($message['data'] as $name => $value)
					$templateProcessor->assign($name, $value);
				try
				{
					$renderedMessages[] = $templateProcessor->fetch($this->getExactModuleTemplateName($type, $message['id'], $message['module']));
				}
				catch (\modules\smarty_based_template_processor\lib\TemplateNotFoundException $e)
				{
					try
					{
						$renderedMessages[] = $templateProcessor->fetch($this->getTemplateName($type, $message['id']));
					}
					catch (\modules\smarty_based_template_processor\lib\TemplateNotFoundException $e)
					{
						$templateProcessor->assign("id", $message['id']);
						$renderedMessages[] = $templateProcessor->fetch($this->getDefaultTemplateName($type));
					}
				}
			}
			$this->deleteAllMessagesByType($type);
			$templateProcessor->assign('messages', $renderedMessages);
			$templateProcessor->assign('typeWrapperTemplate', 'miscellaneous^' . strtolower($type) . '_messages/wrapper.tpl');
			return $templateProcessor->fetch('miscellaneous^messages_wrapper.tpl');
		}
		
		return '';
	}

	private function getTemplateName($type, $id)
	{
		return "miscellaneous^" . strtolower($type) . "_messages/" . strtolower($id) . ".tpl";
	}

	private function getExactModuleTemplateName($type, $id, $module)
	{
		return $module . '^' . strtolower($type) . "_messages/" . strtolower($id) . ".tpl";
	}

	private function getDefaultTemplateName($type)
	{
		return "miscellaneous^" . strtolower($type) . "_messages/default.tpl";
	}

	protected function addMessageByType($message, $type, $moduleName)
	{
		if (is_null($moduleName))
		{
			list($moduleName,) = \App()->ModuleManager->getCurrentModuleAndFunction();
		}
		$message["module"] = $moduleName;
		$messages = $this->dataSource->getValue($type);
		if (is_null($messages)) $messages = array();
		array_push($messages, $message);
		$this->dataSource->setValue($type, $messages);
	}

	protected function getMessages($type)
	{
		return $this->dataSource->getValue($type);
	}

	protected function deleteAllMessagesByType($type)
	{
		$this->dataSource->setValue($type, null);
	}
}

<?php
/**
 *
 *    Module: site_pages v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: site_pages-7.5.0-1
 *    Tag: tags/7.5.0-1@19834, 2016-06-17 13:21:53
 *
 *    This file is part of the 'site_pages' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\site_pages\apps\AdminPanel\scripts;

class AddSitePageHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'site_pages';
	protected $functionName = 'add_site_page';

	public function respond()
	{
		$applicationId = \App()->Request->getValueOrDefault('application_id', 'FrontEnd');
		if (!\App()->doesAppExist($applicationId))
		{
			throw new \lib\Http\NotFoundException('Requested application "' . $applicationId . '" does not exist');
		}

		$uri = \App()->Request['uri'];
        $pageId = \App()->Request['id'];
		if (!empty($uri) && $uri[0] !== '/') $uri = '/' . $uri;

		$page = new \modules\site_pages\lib\SitePage();
		$page->mergeDataWithRequest(array_merge($_REQUEST, array('uri' => $uri, 'application_id' => $applicationId)));
		$pageInfo = $page->getPageInfo();

		if (\App()->Request['action'] == "save")
		{
			if (!empty($uri) && \App()->PageManager->doesPageExists($uri, $applicationId))
			{
				\App()->ErrorMessages->addMessage('PAGE_ALREADY_EXISTS');
			}
            elseif (!empty($pageId) && \App()->PageManager->doesPageIDExists($pageId, $applicationId))
            {
                \App()->ErrorMessages->addMessage('PAGE_ID_ALREADY_EXISTS');
            }
			elseif ($page->isDataValid() && \App()->PageManager->addPage($pageInfo))
			{
				\App()->SuccessMessages->addMessage('PAGE_ADDED');
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'list_site_pages') . "?application_id={$applicationId}");
			}
		}

		$pageInfo['parameters'] = json_encode(!is_array($pageInfo['parameters']) ? array() : $pageInfo['parameters']);

		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('sitePageInfo', $pageInfo);
		$templateProcessor->assign('extraFields', \App()->PageManager->getExtraFields($applicationId));
		$templateProcessor->assign('applicationId', $applicationId);
		$templateProcessor->assign('modulesAndFunctionsData', json_encode(\App()->PageManager->getModuleFunctionParamList($applicationId)));
		$templateProcessor->display('add_site_page.tpl');
	}
}

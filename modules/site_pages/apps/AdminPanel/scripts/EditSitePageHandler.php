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

class EditSitePageHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'site_pages';
	protected $functionName = 'edit_site_page';

	public function respond()
	{
		$applicationId = \App()->Request->getValueOrDefault('application_id', 'FrontEnd');
		if (!\App()->doesAppExist($applicationId))
		{
			throw new \lib\Http\NotFoundException('Requested application "' . $applicationId . '" does not exist');
		}

        $templateProcessor = \App()->getTemplateProcessor();

		$page = new \modules\site_pages\lib\SitePage();


		if (\App()->Request['action'] == "save")
		{
            $pageId = \App()->Request['id'];
            $oldPageId = \App()->Request['oldPageId'];
            $templateProcessor->assign('oldPageId', $oldPageId);

            $oldUri = $this->getUriFromDB($oldPageId, $applicationId);
            $uri = \App()->Request['uri'];
            if (!empty($uri) && $uri[0] !== '/') $uri = '/' . $uri;

            $page->mergeDataWithRequest(array_merge($_REQUEST, array('uri' => $uri, 'application_id' => $applicationId)));
            $pageInfo = $page->getPageInfo();

            $canPerform = true;
			$validators = new \core\ExtensionPoint('modules\site_pages\apps\AdminPanel\IEditSitePageValidator');
			foreach ($validators as $validator)
			{
				$validator->setOldUri($oldUri);
				$validator->setPageInfo($pageInfo);
				$canPerform &= $validator->isValid();
			}
			if ($canPerform)
			{
				if (!empty($uri) && $uri != $oldUri && \App()->PageManager->doesPageExists($uri, $applicationId))
				{
					\App()->ErrorMessages->addMessage('PAGE_ALREADY_EXISTS');
				}
                elseif(!empty($pageId) && $pageId != $oldPageId && \App()->PageManager->doesPageIDExists($pageId, $applicationId))
                {
                    \App()->ErrorMessages->addMessage('PAGE_ID_ALREADY_EXISTS');
                }
				elseif ($page->isDataValid() && \App()->PageManager->updatePage($pageInfo, $oldUri))
				{
					\App()->SuccessMessages->addMessage('PAGE_SAVED');
					throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'list_site_pages') . "?application_id={$applicationId}");
				}
			}
		}
		else
		{
            $pageId = \App()->Request['id'];
            $uri = $this->getUriFromDB($pageId, $applicationId);
			$page->incorporateData(\App()->PageManager->extractPageInfo($uri, $applicationId));
			$templateProcessor->assign('oldPageId', $pageId);
		}

		$pageInfo = $page->getPageInfo();
		$pageInfo['parameters'] = json_encode(!is_array($pageInfo['parameters']) ? array() : $pageInfo['parameters']);

		$templateProcessor->assign('sitePageInfo', $pageInfo);
		$templateProcessor->assign('extraFields', \App()->PageManager->getExtraFields($applicationId));
		$templateProcessor->assign('applicationId', $applicationId);
		$templateProcessor->assign('modulesAndFunctionsData', json_encode(\App()->PageManager->getModuleFunctionParamList($applicationId)));
		$templateProcessor->display('edit_site_page.tpl');
	}

    private function getUriFromDB($pageId, $applicationId)
    {
        $pageInfo = \App()->PageManager->getPageDataById($pageId, $applicationId);
        $uri = $pageInfo['uri'];
        if (!empty($uri) && $uri[0] !== '/') $uri = '/' . $uri;
        return $uri;
    }
}

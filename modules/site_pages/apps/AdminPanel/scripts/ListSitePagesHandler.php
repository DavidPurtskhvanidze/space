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

class ListSitePagesHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\site_pages\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'site_pages';
	protected $functionName = 'list_site_pages';

	public function respond()
	{
		$applicationId = \App()->Request->getValueOrDefault('application_id', 'FrontEnd');
		if (!\App()->doesAppExist($applicationId))
		{
			throw new \lib\Http\NotFoundException('Requested application "' . $applicationId . '" does not exist');
		}

		$sitePageMassActions = new \core\ExtensionPoint('modules\site_pages\lib\ISitePageMassAction');
		if (!is_null(\App()->Request['action']))
		{
			foreach ($sitePageMassActions as $action)
			{
				/**
				 * @var $action \modules\site_pages\lib\ISitePageMassAction
				 */
				if ($action->getId() == \App()->Request['action'] && in_array($applicationId, $action->getApplicationIds()))
				{
					$canPerform = true;
					$validators = new \core\ExtensionPoint('modules\site_pages\lib\ISitePageMassActionExecuteValidator');
					foreach ($validators as $validator)
					{
						$validator->setApplicationId($applicationId);
						$validator->setAction($action);
						$canPerform &= $validator->isValid();
					}
					if ($canPerform)
					{
						$action->perform($applicationId, \App()->Request['site_pages']);
						throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?application_id={$applicationId}");
					}
				}
			}
		}


        $pagesList = \App()->PageManager->getPagesToDisplay($applicationId, \App()->Request['sortingField'], \App()->Request['sortingOrder']);

        $templateProcessor = \App()->getTemplateProcessor();
        $templateProcessor->assign('pages_list', $pagesList);


        $optionalColumnList = $this->prepareColumnsToSelectList($applicationId);
		$templateProcessor->assign('optionalColumnList', $optionalColumnList);
		$templateProcessor->assign('applicationId', $applicationId);
		$templateProcessor->assign('extraFields', \App()->PageManager->getExtraFields($applicationId));
		$templateProcessor->assign('sitePageMassActions', $sitePageMassActions);
		$templateProcessor->assign('appSiteUrl', \App()->SystemSettings->getSettingForApp($applicationId, 'SiteUrl'));
        $templateProcessor->assign('viewActionDisplay', $applicationId == 'SubDomain' ? false : true);
		$templateProcessor->display('site_pages_list.tpl');
	}

	public static function getOrder()
	{
		return 100;
	}

	public function getCaption()
	{
		return "Site Pages";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('site_pages');
	}

	public function getHighlightUrls()
	{
        return array
		(
            array
            (
                'uri' => \App()->PageRoute->getSystemPageURI($this->moduleName, 'edit_site_page'),
                'params' => array('application_id' => 'FrontEnd')
            ),
            array
            (
                'uri' => \App()->PageRoute->getSystemPageURI($this->moduleName, 'list_site_pages'),
                'params' => array('application_id' => 'FrontEnd')
            )
		);
	}

    private function prepareColumnsToSelectList($applicationId)
    {
        $optionalColumnList = \App()->PageManager->getOptionalColumnsList($applicationId); //Спи�?ок в�?ех опциональных значений
        if (!empty(\App()->Request['filter']))//Е�?ли форма была отправлена
        {
            $selectedColumns = is_null(\App()->Request['selectedColumnsList'])?array():\App()->Request['selectedColumnsList']; //Спи�?ок полей, выбранных пользователем или пу�?той ма�?�?ив
            \App()->Cookie->setCookie($applicationId . 'ColumnsToDisplay', serialize($selectedColumns), 365);
        }
        else //Спи�?ок �?охранённых полей или null (Когда форма откывает�?�? первый раз (нет куки�?ов и форма не была отправлена))
        {
            $selectedColumns = !is_null(\App()->Cookie->getCookie($applicationId . 'ColumnsToDisplay')) ? unserialize(\App()->Cookie->getCookie($applicationId . 'ColumnsToDisplay')) : null;
        }

        if (is_null($selectedColumns))// Е�?ли нет куки�?ов и форма не была отправлена
        {
            foreach ($optionalColumnList as &$optionalColumn)
            {
                $optionalColumn['visible'] = $optionalColumn['id'] != 'template';// В�?е колонки видимы, и�?ключение колонка template
            }
        }
        else
        {
            foreach ($optionalColumnList as &$optionalColumn)
            {
                $value = in_array($optionalColumn['id'], $selectedColumns) ? 1 : 0; // Видны отмеченные колонки через форму или из куки�?ов
                $optionalColumn['visible'] = $value;
            }
        }

        return $optionalColumnList;
    }
}

<?php
/**
 *
 *    Module: image_carousel v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: image_carousel-7.5.0-1
 *    Tag: tags/7.5.0-1@19785, 2016-06-17 13:19:31
 *
 *    This file is part of the 'image_carousel' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\image_carousel\apps\AdminPanel\scripts;

use apps\AdminPanel\ContentHandlerBase;
use core\ExtensionPoint;
use lib\Http\RedirectException;
use modules\content_management\apps\AdminPanel\IMenuItem;

class ImageCarouselHandler extends ContentHandlerBase implements IMenuItem
{
	protected $displayName = 'Image Carousel Manager';
	protected $moduleName = 'image_carousel';
	protected $functionName = 'manage_image_carousel';

	public function respond()
	{
		$this->mapActionToMethod
			(
				[
					'SAVE' => [$this, 'actionSaveSettings'],
					'DELETE' => [$this, 'actionDelete'],
					'CHANGE_STATUS' => [$this, 'actionChangeStatus'],
					'MOVE_IMAGE' => [$this, 'actionMoveImage'],
					'SORT' => [$this, 'actionSort'],
                ]
			);

		$this->displayTable();
	}

	private function actionSort()
	{
		$object_replacer = \App()->ObjectMother->createCarouselImagesReplacer(\App()->Request->getValueOrDefault('sortingOrder', null));
		$object_replacer->update();
		die();
	}

	private function actionMoveImage($imagesSids)
	{
		array_map(function ($imageSid) {
			\App()->CarouselImageManager->moveImageBySid($imageSid, \App()->Request['direction']);
		}, $imagesSids);
	}

	private function actionChangeStatus($imagesSids)
	{
		array_map(function ($imageSid) {
			\App()->CarouselImageManager->changeImageStatusBySid($imageSid, \App()->Request['status']);
		}, $imagesSids);
	}

	private function actionSaveSettings()
	{
		\App()->SettingsFromDB->updateSettings(\App()->Request->getRequest());
		\App()->SuccessMessages->addMessage('SETTINGS_UPDATED');
	}

	private function actionDelete($imagesSids)
	{
		$canPerform = true;
		$validators = new ExtensionPoint('modules\image_carousel\apps\AdminPanel\IDeleteCarouselImageValidator');
		foreach ($validators as $validator) {
			$canPerform &= $validator->isValid();
		}

		if ($canPerform) {
			array_map(function ($imageSid) {
				\App()->CarouselImageManager->deleteImageBySid($imageSid);
			}, $imagesSids);
		}

	}

	private function displayTable()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$templateProcessor->assign("images", \App()->CarouselImageManager->getCollectionForTemplate(1000));
		$templateProcessor->assign("frontEndSiteUrl", \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl'));
		$templateProcessor->assign("settings", \App()->SettingsFromDB->getSettings());
		$templateProcessor->assign('checkedImages', isset($_REQUEST['images']) ? $_REQUEST['images'] : array());
		$templateProcessor->display('manage_image_carousel.tpl');
	}

	private function mapActionToMethod($map)
	{
		if (null !== ($action = \App()->Request->getValueOrDefault('action', null))) {
			if (null !== ($imagesSids = \App()->Request->getValueOrDefault('images', null))
				|| in_array($action, array('sort', 'save'))) {
				$action = strtoupper($action);
				if (isset($map[$action])) {
					call_user_func($map[$action], $imagesSids);
					throw new RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'manage_image_carousel') . '?' . http_build_query(array('images' => $imagesSids)));
				}
			}
		}
	}

	public function getCaption()
	{
		return "Image Carousel";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array
		(
            \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'add_carousel_image'),
		);
	}

	public static function getOrder()
	{
		return 600;
	}
}

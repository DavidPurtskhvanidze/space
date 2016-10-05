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

use apps\AdminPanel\ContentHandlerBase;
use lib\Http\ForbiddenException;
use lib\ORM\ObjectProperty;
use modules\main\lib\HandlerException;

class PictureActionsHandler extends ContentHandlerBase
{
    protected $displayName = 'Pictures Actions';
    protected $moduleName = 'classifieds';
    protected $functionName = 'picture_actions';
    protected $rawOutput = true;
    protected $skipTokenMatch = true;

    /**
     * @var \modules\classifieds\lib\Listing\Listing
     */
    private $listing;
    private $pictureSid;

    /**
     * @var \modules\classifieds\lib\ListingGallery\ListingGallery
     */
    private $listingGallery;

    public function respond()
    {
        try {
            $this->init();

            switch (\App()->Request['action']) {
                case 'change_order':
                    $listingPictureSidsOrdered = (array)\App()->Request['picture'];
                    $this->listingGallery->setPicturesOrder($listingPictureSidsOrdered);
                    break;
                case 'delete':
                    $this->listingGallery->deleteImageBySID($this->pictureSid);
                    break;
                case 'update_caption':
                    $this->listingGallery->updatePictureCaption($this->pictureSid, \App()->Request['picture_caption']);
                    break;
                case 'upload':
                    $this->actionUploadPicture();
                    break;
                default:
                    throw new HandlerException("PARAMETERS_MISSED");
            }
        } catch (HandlerException $e) {
            $tp = \App()->getTemplateProcessor();
            $tp->assign('error', $e->getMessage());
            throw new ForbiddenException($tp->fetch('pictures_actions_error.tpl'));
        }
    }

    private function init()
    {
        $listingSid = \App()->Request['listing_sid'];
        if (is_null($listingSid)) {
            throw new HandlerException('PARAMETERS_MISSED');
        }
        if (is_null($this->listing = \App()->ListingManager->getObjectBySID($listingSid))) {
            throw new HandlerException('WRONG_PARAMETERS_SPECIFIED');
        }
        $this->listingGallery = \App()->ListingGalleryManager->createListingGallery();
        $this->listingGallery->setListingSID($this->listing->getSID());

        $this->pictureSid = \App()->Request['picture_sid'];
        $this->listingGallery->setListing($this->listing);

        if (!is_null($this->pictureSid)) {
            if ($this->listingGallery->getListingSidByPictureSid($this->pictureSid) != $this->listing->getSID()) {
                throw new HandlerException('NOT_OWNER');
            }
        }
    }

    private function actionUploadPicture()
    {
        header('Content-type: text/plain');
        try {
            /**
             * @var ObjectProperty $pictureProperty
             */
            $pictureProperty = $this->listing->getProperty('pictures');
            $pictureProperty->type->setPicLimit(0);
            if (!$pictureProperty->isValid()) {
                $response = [
                    'success' => false,
                    'error' => $pictureProperty->type->getValidationErrorMessage(),// NOT_SUPPORTED_IMAGE_FORMAT, UPLOAD_ERR_*, NOT_OWNER, PICTURES_LIMIT_EXCEEDED
                    'pictureInfo' => [],
                ];
            } else {

                $pictureProperty->type->afterUpdate(function ($uploader) {
                    $order = \App()->DB->getSingleValue("SELECT count(*) FROM `classifieds_listings_pictures` WHERE `listing_sid` = ?n", $uploader->getParentKey());
                    \App()->DB->query("UPDATE `classifieds_listings_pictures` SET `order` = ?n WHERE `sid` = ?n", $order, $uploader->getKey());
                });

                $pictureInfo = $pictureProperty->type->upload();

                $response = [
                    'success' => true,
                    'error' => '',
                    'pictureInfo' => $pictureInfo,
                ];
                $this->listingGallery->setListingPictureAmount($this->listingGallery->getPicturesAmount());
            }

        } catch (\Exception $e) {
            $response =
                [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'pictureInfo' => [],
                ];
        }

        echo json_encode($response);
    }

}

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


namespace modules\classifieds\apps\FrontEnd\scripts;

use apps\FrontEnd\ContentHandlerBase;
use core\ExtensionPoint;
use lib\Forms\Form;
use modules\ip_blocklist\lib\IpProcessor;

class EditListingHandler extends ContentHandlerBase
{
    protected $displayName = 'Edit Listing';
    protected $moduleName = 'classifieds';
    protected $functionName = 'edit_listing';

    public function respond()
    {
        $template_processor = \App()->getTemplateProcessor();
        $listing_id = $this->getListingId();
        $listing_info = \App()->ListingManager->getListingInfoBySID($listing_id);
        $listingEdited = false;

        $template = 'edit_listing.tpl';

        if ($listing_info['user_sid'] != \App()->UserManager->getCurrentUserSID()) {
            \App()->ErrorMessages->addMessage('NOT_OWNER_OF_LISTING', ['listingId' => $listing_id]);
            $template = 'edit_listing_error.tpl';
        } elseif (!is_null($listing_info)) {
            $listing_info = \App()->ListingManager->listingInfoI18N($listing_info);
            $listing_info = array_merge($listing_info, \App()->Request->getRequest());
            $listing = \App()->ObjectMother->getListingFactory()->getListing($listing_info, $listing_info['category_sid']);
            $listing->setSID($listing_id);

            $propertiesToExclude = ['sid', 'activation_date', 'expiration_date', 'type', 'category_sid', 'active', 'id', 'moderation_status', 'views', 'package', 'listing_package', 'pictures', 'user', 'username', 'user_sid', 'keywords', 'category', 'meta_keywords', 'meta_description', 'page_title'];
            array_walk($propertiesToExclude, [$listing, 'deleteProperty']);

            $listing_structure = \App()->ListingManager->createTemplateStructureForListing($listing);
            \App()->ListingFeaturesManager->disableModifyingListingFeatures($listing);

            $listing_edit_form = new Form($listing);
            $listing_edit_form->registerTags($template_processor);

            $template_processor->registerPlugin('block', 'step', [$this, 'registerStep']);
            $template_processor->registerPlugin('function', 'editListingForm', [$this, 'editListingForm']);

            $form_is_submitted = (isset(\App()->Request['action']) && \App()->Request['action'] == 'save_info');

            if ($form_is_submitted && $listing_edit_form->isDataValid()) {
                $canPerform = true;
                $validators = new ExtensionPoint('modules\classifieds\apps\FrontEnd\IEditListingValidator');
                foreach ($validators as $validator) {
                    $validator->setListing($listing);
                    $canPerform &= $validator->isValid();
                }
                if ($canPerform) {
                    $listing->addLastUserIpProperty(IpProcessor::getClientIpAsString());
                    \App()->ListingManager->saveListing($listing);
                    \App()->ListingManager->updateListingStatusAfterModified($listing->getSID());

                    if (\App()->ListingManager->getActiveStatus($listing_id)) {
                        \App()->SuccessMessages->addMessage('LISTING_MODIFIED_AND_ACTIVE');
                    } elseif (\App()->ListingManager->getModerationStatus($listing_id) == 'PENDING') {
                        \App()->SuccessMessages->addMessage('LISTING_PENDING_APPROVAL');
                    } else {
                        \App()->SuccessMessages->addMessage('LISTING_MODIFIED');
                    }
                    $listingEdited = true;

                    $afterEditActions = new ExtensionPoint('modules\classifieds\apps\FrontEnd\IAfterEditListingAction');
                    foreach ($afterEditActions as $action) {
                        $action->setListing($listing);
                        $action->perform();
                    }
                }
            }
            $template_processor->assign("listing", $listing_structure);
            $template_processor->assign("display_sold_field", true);
            $template_processor->assign("package", $package_info = \App()->ListingPackageManager->createTemplateStructureForPackageByListingSID($listing->getSID()));
            $template_processor->assign("ancestors", array_reverse(\App()->CategoryTree->getAncestorsInfo($listing_info['category_sid'])));
            $template_processor->assign("form_fields", $listing_edit_form->getFormFieldsInfo());
            $template_processor->assign("calendarTypeFieldIds", $listing_edit_form->getFormFieldsIdsByType('calendar'));
            $template_processor->assign("not_valid_property_ids", $listing_edit_form->getNotValidPropertyIds());

        }

        $template_processor->assign('inputFormTemplate', \App()->CategoryManager->getCategoryInputTemplateFileName($listing_info['category_sid']));
        $template_processor->assign('listingAction', 'edit');
        $template_processor->assign('listingEdited', $listingEdited);
        $template_processor->display($template);
    }

    private function getListingId()
    {
        if (isset($_REQUEST['passed_parameters_via_uri'])) {
            $parameters_via_url = \App()->UrlParamProvider->getParams();
            $listing_id = isset($parameters_via_url[0]) ? $parameters_via_url[0] : null;
        } elseif (isset($_REQUEST['listing_id'])) {
            $listing_id = $_REQUEST['listing_id'];
        } else {
            $listing_id = null;
        }
        return $listing_id;
    }


    private $steps = [];

    /**
     * @param $params
     * @param $content
     * @param \Smarty_Internal_Template $template
     * @param $repeat
     * @return string
     */
    public function registerStep($params, $content, $template, &$repeat)
    {
        if ($repeat) {
            return "";
        }
        $stepNumber = count($this->steps) + 1;
        $this->steps[$stepNumber] = $params;
        return sprintf('<div class="step" id="step%d">%s</div>', $stepNumber, $content);
    }

    /**
     * @param $params
     * @param \Smarty_Internal_Template $template
     */
    public function editListingForm($params, $template)
    {
        $templateProcessor = $template->smarty;

        $formTemplate = $params['formTemplate'];
        // важно!  $template->fetch($formTemplate) нужно вызвать перед определением количе�?тва в�?ех шагов
        $formContent = $templateProcessor->fetch($formTemplate);

        $templateProcessor->assign("id", $params['id']);
        $templateProcessor->assign("formContent", $formContent);
        $templateProcessor->assign("steps", $this->steps);
        $templateProcessor->display("edit_listing_form.tpl");
    }
}

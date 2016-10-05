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

class AddNewMakeModelRequest extends \apps\FrontEnd\ContentHandlerBase
{
    protected $displayName = 'Add New Make Model Request';
    protected $moduleName = 'classifieds';
    protected $functionName = 'add_new_make_model_request';
    protected $rawOutput = true;


    public function respond()
    {
        $templateProcessor = \App()->getTemplateProcessor();
        if (\App()->Request['action'] == 'send_message')
        {
            if ($this->isFormValid())
            {
                $messageTemplate = "classifieds^admin_add_new_make_model_request.tpl";
                $params['username'] = \App()->UserManager->getCurrentUser()->getUserName();
                $params['comments'] = trim(\App()->Request['comments']);
                \App()->EmailService->sendToAdmin($messageTemplate, $params);
                $templateProcessor->assign('message_sent', true);
            }
            else
            {
                $templateProcessor->assign('noCommentError', true);
            }
        }
        $templateProcessor->display('add_new_make_model_request.tpl');
    }

    private function isFormValid()
    {
        $comment = trim(\App()->Request['comments']);
        return empty($comment) ? false : true;
    }
}

?>

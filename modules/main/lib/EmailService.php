<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\main\lib;

class EmailService implements \core\IService
{
    public function createEmail($to, $template, $data, $replyTo = null)
    {
        $email = new Email(\App()->SettingsFromDB->getSettingByName('system_email'), $to, $template, $data);
        if (!is_null($replyTo))
        {
            $email->setReplyTo($replyTo);
        }
        return $email;
    }

    public function send($to, $template, $data, $replyTo = null)
    {
        $email = $this->createEmail($to, $template, $data, $replyTo);
        return $email->send();
    }

    public function sendToAdmin($template, $data, $replyTo = null, $recipientEmail = null)
    {
        $recipientEmail = is_null($recipientEmail)?\App()->SettingsFromDB->getSettingByName('notification_email'):$recipientEmail;
        return $this->send($recipientEmail, $template, $data, $replyTo);
    }
}

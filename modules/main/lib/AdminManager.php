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

class AdminManager
{
    public function admin_authed()
    {
        if (\App()->Session->getValue('usertype') == 'admin') return true;
        return false;
    }

    public function admin_login($username, $password)
    {
        $result = \App()->DB->query('SELECT * FROM `core_administrator` WHERE `username`=?s AND `password`=PASSWORD(?s)', $username, $password);
        if (count($result) < 1)
        {
            \App()->ErrorMessages->addMessage("WRONG_PASSWORD");
            return false;
        }
        if (!\App()->AccessControlManager->isGroupActive($result[0]['group']))
        {
	       \App()->ErrorMessages->addMessage("INACTIVE_USER");
	        return false;
        }
        if (!\App()->AccessControlManager->isAdministratorActive($username))
        {
	        \App()->ErrorMessages->addMessage("INACTIVE_USER");
	        return false;
        }

        \App()->AccessControlManager->setAccessDataControlByUsername($result[0]['username']);
        \App()->Session->setValue('username', $result[0]['username']);
        \App()->Session->setValue('usertype', "admin");
        return true;
    }

    public function admin_log_out()
    {
        \App()->AccessControlManager->onLogout();
        \App()->Session->unsetValue('username');
        \App()->Session->unsetValue('usertype');
    }

    public function sendPasswordRecoveryEmail($adminUsername)
    {
        if (is_null($adminUsername))
            return false;

        $result = \App()->DB->query('SELECT * FROM `core_administrator` WHERE `username` = ?s', $adminUsername);

        if (empty($result))
        {
            \App()->ErrorMessages->addMessage("WRONG_ADMIN_USERNAME");
            return false;
        }

        if (!\App()->AccessControlManager->isAdministratorActive($adminUsername))
        {
            \App()->ErrorMessages->addMessage("INACTIVE_USER");
            return false;
        }

        $verificationKey = $this->createUniqueKey();
        \App()->DB->query('UPDATE `core_administrator` SET `verification_key`=?s WHERE `username` = ?s', $verificationKey, $adminUsername);
        $adminInfo = $result[0];
        $adminInfo['verification_key'] = $verificationKey;
        $recipientEmail = \App()->AccessControlManager->getAdminEmailByUsername($adminInfo['username']);

        if (!\App()->EmailService->sendToAdmin('email_template:password_change_email', array('adminInfo' => $adminInfo), null, $recipientEmail))
        {
            \App()->ErrorMessages->addMessage("EMAIL_SENDING_FAILED");
            return false;
        }
        return true;
    }

    public function changeAdminPassword($username, $password, $passwordConfirmation)
    {
        if (empty($password) && empty($passwordConfirmation))
        {
            \App()->ErrorMessages->addMessage("EMPTY_VALUE", array('fieldCaption' => 'Password'));
            return false;
        }

        if ($password != $passwordConfirmation)
        {
            \App()->ErrorMessages->addMessage("NOT_CONFIRMED", array('fieldCaption' => 'Password'));
            return false;
        }

        \App()->DB->query('UPDATE `core_administrator` SET `password` = PASSWORD(?s), `verification_key` = NULL WHERE `username`=?s', $password, $username);
        return true;
    }

    public function getAdminVerificationKey($username)
    {
        return \App()->DB->getSingleValue('SELECT `verification_key` FROM `core_administrator` WHERE `username`=?s', $username);
    }

    private function createUniqueKey()
    {
        $symbols = array_merge( range('a','z'), range('0','9') );
        shuffle($symbols);
        return join('', $symbols);
    }

    public function getAdminGroupByUsername($username)
    {
        $result = \App()->DB->query('SELECT * FROM `core_administrator` WHERE `username`=?s', $username);
        if (count($result) > 0)
        {
            return $result[0]['group'];
        }
        return false;
    }
}

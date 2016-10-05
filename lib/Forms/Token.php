<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */

namespace lib\Forms;

use core\IService;

class Token implements IService
{

    public function init()
    {
        if ($this->isReading())
        {
            $this->generate();
        }
    }

    protected function generate()
    {
        $maxTime = 60 * 60; // token is valid for 1 hour
        $secureToken = \App()->Session->getValue('secure_token');
        $storedTime = \App()->Session->getValue('secure_token_time');

        if ($maxTime + $storedTime <= time() || empty($secureToken)) {
            \App()->Session->setValue('secure_token', md5(uniqid(rand(), true)));
            \App()->Session->setValue('secure_token_time', time());
        }

        return \App()->Session->getValue('secure_token');
    }

    protected function isReading()
    {
        return in_array(\App()->Request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }



    /**
     * checks if CSRF token in session is same as in the form submitted
     * @return bool
     */
    public function isValid()
    {
        return \App()->Request['secure_token'] === \App()->Session->getValue('secure_token');
    }

    public function getToken()
    {
        return \App()->Session->getValue('secure_token');
    }

}

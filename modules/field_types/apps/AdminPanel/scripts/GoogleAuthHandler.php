<?php
/**
 *
 *    Module: field_types v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: field_types-7.5.0-1
 *    Tag: tags/7.5.0-1@19782, 2016-06-17 13:19:23
 *
 *    This file is part of the 'field_types' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\field_types\apps\AdminPanel\scripts;

class GoogleAuthHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $displayName = 'GoogleAuthHandler';
	protected $moduleName = 'field_types';
	protected $functionName = 'google_auth';
	protected $rawOutput = true;

    /**
     * @var \modules\field_types\lib\YouTubeVideoManager
     */
    private $youTubeVideoManager;
    private $client;

    public function __construct()
    {
        $this->youTubeVideoManager = new \modules\field_types\lib\YouTubeVideoManager();
        $this->client = $this->youTubeVideoManager->getClient();
    }

	public function respond()
	{

        if ($this->youTubeVideoManager->isTokenDefined())
        {
            $this->refreshToken();
        }
        else
        {
            $this->getAccessToken();
        }
	}

    private function refreshToken()
    {
        try
        {
            $this->youTubeVideoManager->refreshTokenIfExpired();
        }
        catch(\Exception $e)
        {
            \App()->ErrorMessages->addMessage($e->getMessage());
        }
        throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('settings'));
    }

    private function getAccessToken()
    {
        $this->client->setRedirectUri(\App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName));

        if (isset(\App()->Request['code']))
        {
            if (strval(\App()->Session->getValue('state', 'youtube')) !== strval(\App()->Request['state']))
            {
                throw new \modules\field_types\lib\Exception('The session state did not match.');
            }

            $this->client->authenticate(\App()->Request['code']);
            $this->youTubeVideoManager->saveToken($this->client->getAccessToken());

        }

        if ($this->client->getAccessToken())
        {
            $this->youTubeVideoManager->saveToken($this->client->getAccessToken());
            throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('settings'));
        }
        else
        {
            $state = mt_rand();
            $this->client->setState($state);
            \App()->Session->setValue('state', $state, 'youtube');
            throw new \lib\Http\RedirectException($this->client->createAuthUrl());
        }
    }
}

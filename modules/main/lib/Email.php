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

use modules\smarty_based_template_processor\lib\resource\Email as EmailResource;

class Email
{
	protected $text;
	protected $subject;
	protected $recipientEmail;
	protected $replyTo;
	protected $from;
	protected $images;

	public function __construct($from, $recipientEmail, $template, $data)
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$this->from = $from;
		$this->recipientEmail = $recipientEmail;
        $emailRes = new EmailResource($templateProcessor, $this);
        $templateProcessor->registerResource('email_template', $emailRes);
		foreach ($data as $key => $value)
		{
			$templateProcessor->filterThenAssign($key, $value);
		}
		$templateProcessor->fetch($template);
	}

	public function getText()
	{
		return $this->text;
	}

	public function setReplyTo($replyTo)
	{
		$this->replyTo = $replyTo;
	}
    
	public function send()
	{
		if (empty($this->recipientEmail) || empty($this->subject)) return false;
		$this->replaceImages();
		$mailer = new \PHPMailer();
        $mailer->CharSet = 'UTF-8';
		$mailer->setFrom($this->from, $this->from);

		if (!empty($this->replyTo))
		{
			$mailer->addReplyTo($this->replyTo, $this->replyTo);
		}

		$mailer->addAddress($this->recipientEmail);
		$mailer->Subject = $this->subject;

		if($this->images)
		{
			foreach($this->images as $imgName => $img)
			{
				$mailer->addEmbeddedImage($img, $imgName);
			}
		}
		$mailer->msgHTML($this->text);
		return $mailer->send();

	}

	private function replaceImages()
	{
		$images = $this->getImages();

		foreach($images as $image)
		{
			$info = pathinfo($image);
			$this->text = str_replace($image, 'cid:' . $info['basename'], $this->text);
			$this->images[$info['basename']] = str_replace(\App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl'), PATH_TO_ROOT, $image);
		}
	}

	private function getImages()
	{
		$images = array();
		preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $this->text, $media);
		unset($data);
		$data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);

		foreach ($data as $url)
		{
			$info = pathinfo($url);
			if (isset($info['extension']))
			{
				if (($info['extension'] == 'jpg') ||
					($info['extension'] == 'jpeg') ||
					($info['extension'] == 'gif') ||
					($info['extension'] == 'png'))
					array_push($images, $url);
			}
		}
		return $images;
	}

    /**
     * @param string $subject
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param string $text
     * @return Email
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
}

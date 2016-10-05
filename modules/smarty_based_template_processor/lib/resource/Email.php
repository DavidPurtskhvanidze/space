<?php
/**
 *
 *    Module: smarty_based_template_processor v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: smarty_based_template_processor-7.5.0-1
 *    Tag: tags/7.5.0-1@19835, 2016-06-17 13:21:56
 *
 *    This file is part of the 'smarty_based_template_processor' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\smarty_based_template_processor\lib\resource;


class Email extends \Smarty_Resource_Custom
{

    private $templateProcessor;
    private $email;
    private $db;

    public function __construct(&$templateProcessor, &$email)
    {
        $templateProcessor->registerPlugin('block', 'subject', [$this, 'parseLetterSubject']);
        $templateProcessor->registerPlugin('block', 'message', [$this, 'parseLetterMessage']);

        $this->templateProcessor = $templateProcessor;
        $this->email = $email;
        $this->db = \App()->DB;
    }

    public function parseLetterSubject($params, $content, &$template_processor, &$repeat)
    {
        $this->email->setSubject($content);
    }

    public function parseLetterMessage($params, $content, &$template_processor, &$repeat)
    {
        $this->email->setText($content);
    }

    /**
     * fetch template and its modification time from data source
     *
     * @param string $name template name
     * @param string &$source template source
     * @param integer &$mtime template modification timestamp (epoch)
     */
    protected function fetch($name, &$source, &$mtime)
    {
        $templateRow = $this->db->getSingleRow("SELECT * FROM `email_templates` WHERE `id` = ?s", $name);

        if (!empty($templateRow))
        {

            $source = '{subject}' . $templateRow['subject'] . '{/subject}';
            $source .= '{message}' . $templateRow['body'] . '{/message}';
            $mtime = strtotime($templateRow['last_modified']);

        } else {

            $source = null;
            $mtime = null;

        }
    }

    protected function fetchTimestamp($name)
    {
        return strtotime($this->db->getSingleValue("SELECT `last_modified` FROM `email_templates` WHERE `id` = ?s", $name));
    }
}

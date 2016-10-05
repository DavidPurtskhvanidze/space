<?php
/**
 *
 *    Module: poll v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: poll-7.5.0-1
 *    Tag: tags/7.5.0-1@19804, 2016-06-17 13:20:21
 *
 *    This file is part of the 'poll' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\poll\apps\FrontEnd\scripts;

class PollBlockHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Poll block';
	protected $moduleName = 'poll';
	protected $functionName = 'poll_form';

	public function respond()
	{
		$poll = new \modules\poll\lib\Poll();
		$questions = $poll->getQuestions();
		if (count($questions) > 0)
		{
			$template_processor = \App()->getTemplateProcessor();
			shuffle($questions);
			$question = array_pop($questions);
			$answers = $poll->get_answers($question['id']);
			$template_processor->assign('question', $question);
			$template_processor->assign('answers', $answers);
			$template_processor->display('poll_block.tpl');
		}
	}
}
?>

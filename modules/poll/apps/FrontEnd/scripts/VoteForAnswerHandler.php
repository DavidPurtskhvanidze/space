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

class VoteForAnswerHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Vote for answer';
	protected $moduleName = 'poll';
	protected $functionName = 'vote_for_answer';
	protected $parameters = array('votequestion', 'voteanswer');

	public function respond()
	{
		$poll = new \modules\poll\lib\Poll();
		if (isset($_REQUEST['votequestion'], $_REQUEST['voteanswer']))
		{
			if (!in_array($_REQUEST['votequestion'], $poll->getVotedQuestions()))
			{
				$poll->increaseAnswerCounter($_REQUEST['voteanswer']);
				$poll->setQuestionInactive($_REQUEST['votequestion']);
			}
		}
		throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath('poll', 'vote_results'));
	}
}
?>

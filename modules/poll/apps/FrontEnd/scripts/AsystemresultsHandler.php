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

class AsystemresultsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Vote Results';
	protected $moduleName = 'poll';
	protected $functionName = 'vote_results';

	public function respond()
	{
		$poll = new \modules\poll\lib\Poll();
		$questions = $poll->get_questions();
		$colors = $poll->getColorsOfObjects();
		foreach ($questions as $questionKey => $question) {
			$questions[$questionKey]['answers'] = $poll->get_answers($question['id']);
			if (!$poll->isQuestionDisplayed($question) || empty($questions[$questionKey]['answers']))
			{
				unset($questions[$questionKey]);
			}
			else
			{
				$questions[$questionKey]['total_votes'] = $poll->get_100p($question['id']);
				$answerCounter = 0;
				foreach ($questions[$questionKey]['answers'] as $answerKey => $answer)
				{
					$questions[$questionKey]['answers'][$answerKey]['rate'] = ($questions[$questionKey]['total_votes']) ? round($answer['counter'] * 100 / $questions[$questionKey]['total_votes'], 1) : 0;
					$questions[$questionKey]['answers'][$answerKey]['back_color'] = $colors['back_color'][$answerCounter % count($colors['back_color'])];
					$questions[$questionKey]['answers'][$answerKey]['text_color'] = $colors['text_color'][$answerCounter % count($colors['text_color'])];
					$answerCounter++;
				}
			}
		}
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign ("questions", $questions);
		$template_processor->display ("poll_results.tpl");
	}
}
?>

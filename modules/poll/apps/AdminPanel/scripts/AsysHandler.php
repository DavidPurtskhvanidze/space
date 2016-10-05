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


namespace modules\poll\apps\AdminPanel\scripts;

class AsysHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\content_management\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'poll';
	protected $functionName = 'polls_admin';

	private $poll;
	public function respond()
	{
		$this->poll = new \modules\poll\lib\Poll();
		if(isset($_REQUEST['action']))
		{
			if($_REQUEST['action']=='createquestion')
			{
				if (empty ($_REQUEST['newquestion'])) $_REQUEST['newquestion'] = " *New question* ";
				$question_fields = array
					(
						'title'		=> $_REQUEST['newquestion'],
						'comment'	=> "Date created: ".date("d.m.y"),
						'activity'	=> false,
						'display'	=> false,
					);
				$this->poll->create_question($question_fields);
				throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?question_id=".\App()->DB->insert_id());
			}
			if($_REQUEST['action']=='deletequestion')
			{
				if (isset($_REQUEST['question_id']) && $this->poll->question_exists($_REQUEST['question_id']))
				{
					$canPerform = true;
					$validators = new \core\ExtensionPoint('modules\poll\apps\AdminPanel\IDeletePollQuestionValidator');
					foreach ($validators as $validator)
					{
						$validator->setQuestionId($_REQUEST['question_id']);
						$canPerform &= $validator->isValid();
					}

					if ($canPerform)
					{
						$this->poll->delete_question($_REQUEST['question_id']);
					}
					else
					{
						throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI());
					}
				}
			}
			if($_REQUEST['action']=='changequestion')
			{
				if(isset($_REQUEST['question_id'],$_REQUEST['title']))
					if($this->poll->question_exists($_REQUEST['question_id']))
					{
						$question_fields = array
							(
								'id'			=> $_REQUEST['question_id'],
								'title'			=> $_REQUEST['title'],
								'activity'		=> $_REQUEST['activity'],
								'display'		=> $_REQUEST['display'],
							);
						$this->poll->edit_question($question_fields);
					}

			}
			if($_REQUEST['action']=='createanswer')
			{
				if (empty ($_REQUEST['newanswer'])) $_REQUEST['newanswer'] = " *New answer* ";
				if(isset($_REQUEST['question_id']))
					if($this->poll->question_exists($_REQUEST['question_id']))
						$this->poll->create_answer($_REQUEST['newanswer'], $_REQUEST['question_id']);
				throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?question_id=".$_REQUEST['question_id'].'&answer_id='.\App()->DB->insert_id ());
			}
			if($_REQUEST['action']=='deleteanswer')
			{
				if (isset($_REQUEST['question_id'],$_REQUEST['answer_id']) && $this->poll->answer_exists($_REQUEST['question_id'],$_REQUEST['answer_id']))
				{
					$canPerform = true;
					$validators = new \core\ExtensionPoint('modules\poll\apps\AdminPanel\IDeletePollAnswerValidator');
					foreach ($validators as $validator)
					{
						$validator->setAnswerId($_REQUEST['answer_id']);
						$canPerform &= $validator->isValid();
					}

					if ($canPerform)
					{
						$this->poll->delete_answer($_REQUEST['answer_id']);
					}
					else
					{
						throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . '?question_id=' . $_REQUEST['question_id'] . '&answer_id=' . $_REQUEST['answer_id']);
					}
				}
			}
			if($_REQUEST['action']=='changeanswer')
			{
				if(isset($_REQUEST['question_id'],$_REQUEST['answer_id'],$_REQUEST['title'],$_REQUEST['counter']))
					if($this->poll->answer_exists($_REQUEST['question_id'],$_REQUEST['answer_id'])) $this->poll->edit_answer($_REQUEST['answer_id'],$_REQUEST['title'],$_REQUEST['counter']);
			}
		}

		if (isset($_REQUEST['question_id']) && $this->poll->question_exists($_REQUEST['question_id']))
		{
			$this->displayEditQuestion($_REQUEST['question_id']);
			$this->displayQuestionAnswers($_REQUEST['question_id']);
		}
		else
		{
			$this->displayQuestions();
		}
	}
	private function displayEditQuestion($questionId)
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("question", $this->poll->get_question($questionId));
		$template_processor->display("edit_question_form.tpl");
	}
	private function displayQuestionAnswers($questionId)
	{
		$answers = $this->poll->get_answers($questionId);
		$total_answers = $this->poll->get_100p($questionId);
		$colors = $this->poll->getColorsOfObjects();
		$i = 0;
		foreach ($answers as $answer_id => $answer)
		{
			$answers[$answer_id]['back_color'] 	= $colors['back_color'][$i % count($colors['back_color'])];
			$answers[$answer_id]['text_color'] 	= $colors['text_color'][$i % count($colors['text_color'])];
			$answers[$answer_id]['ratio']		= $total_answers ? round ($answer['counter'] / $total_answers * 100, 1) : 0;
			$i++;
		}
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("total_answers", $total_answers);
		$template_processor->assign("answers", $answers);
		$template_processor->assign("question_id", $questionId);
		$template_processor->display("answer_list.tpl");
	}
	private function displayQuestions()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("questions", $this->poll->get_questions(\App()->Request['sortingField'], \App()->Request['sortingOrder']));
		$template_processor->display("question_list.tpl");
	}

	public function getCaption()
	{
		return "Online Polls";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('polls');
	}

	public function getHighlightUrls()
	{
		return array();
	}

	public static function getOrder()
	{
		return 200;
	}
}

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


namespace modules\poll\lib;

class Poll
{
	public function getColorsOfObjects()
	{
		return array(
			'back_color' => array(
				'#CC6666',
				'#9999FF',
				'#99CC00',
				'#f0f0f0',
				'#6699CC',
				'#800080',
				'#000000'),
			'text_color' => array(
				'#FFFFFF',
				'#FFFFFF',
				'#FFFFFF',
				'#000000',
				'#000000',
				'#FFFFFF',
				'#FFFFFF'));
	}

	function get_100p($question_id)
	{
		$r = \App()->DB->query("SELECT SUM(`counter`) FROM `poll_answers` WHERE `question_id` = ?s", $question_id);
		return array_pop($r[0]);
	}

	function create_question($question_fields)
	{
		if(empty($question_fields['title']))
		{
			return false;
		}
		return (bool) \App()->DB->query("INSERT INTO `poll_questions`(`title`, `comment`) VALUES(?s, ?s)", $question_fields['title'], $question_fields['comment']);
	}

	function create_answer($title,$question_id)
	{
		if(empty($title))
		{
			return false;
		}
		return (bool) \App()->DB->query("INSERT INTO `poll_answers` (`title`,`question_id`) VALUES(?s, ?s)", $title, $question_id);
	}

	function delete_question($question_id)
	{
		$res1 = \App()->DB->query("DELETE FROM `poll_answers` WHERE `question_id` = ?s", $question_id);
		if($res1 === false)
		{
			return false;
		}
		return (bool) \App()->DB->query("DELETE FROM `poll_questions` WHERE `id` = ?s", $question_id);
	}

	function delete_answer($answer_id)
	{
		return (bool) \App()->DB->query("DELETE FROM `poll_answers` WHERE `id` = ?s", $answer_id);
	}

	function edit_question($question_fields)
	{
		if(empty($question_fields['title']))
		{
			return false;
		}
		return (bool) \App()->DB->query( "UPDATE `poll_questions` SET `title`=?s, `activity`=?n, `display`=?n WHERE `id` = ?s",
			$question_fields['title'], $question_fields['activity'], $question_fields['display'], $question_fields['id'] );
	}

	function edit_answer($answer_id,$title,$counter)
	{
		return (bool) \App()->DB->query( "UPDATE `poll_answers` SET `title` = ?s, `counter` = ?s WHERE `id` = ?s", $title, $counter, $answer_id);
	}

	function get_question($question_id)
	{
		return \App()->DB->getSingleRow("SELECT * FROM `poll_questions` WHERE `id`=?n", $question_id);
	}

	function get_questions($sortingField = 'none', $sortingOrder = 'none')
	{
		static $sortingOrders = array('ASC', 'DESC');
		static $sortableFields = array('title');
		if (!in_array($sortingOrder, $sortingOrders))
		{
			$sortingOrder = 'ASC';
		}
		if (!in_array($sortingField, $sortableFields))
		{
			$sortingField = $sortableFields[0];
		}
		
		return \App()->DB->query("SELECT * FROM `poll_questions` ORDER BY `$sortingField` $sortingOrder");
	}

	function get_answers($question_id)
	{
		return \App()->DB->query("SELECT * FROM `poll_answers` WHERE `question_id` = ?s", $question_id);
	}

	function answer_exists($question_id,$answer_id)
	{
		$r = \App()->DB->query("SELECT * FROM `poll_answers` WHERE `id` = ?s", $answer_id);
		return count($r) === 1;
	}

	function increase_counter($question_id,$answer_id)
	{
		return \App()->DB->query("UPDATE `poll_answers` SET `counter`=`counter`+1 WHERE `id`=?n", $answer_id);
	}

	function question_exists($question_id)
	{
		$r = \App()->DB->query("SELECT * FROM `poll_questions` WHERE `id`=?n", $question_id);
		return count($r) === 1;
	}
	
	function getQuestions()
	{
		$all_questions = $this->get_questions();
		$questions = array();
		foreach($all_questions as $question)
			if($question['activity'])
				$questions[$question['id']] = $question;
		return $this->_unsetVotedQuestions($questions);
	}

	function _unsetVotedQuestions(&$questions)
	{
		$voted_questions = $this->getVotedQuestions();
		foreach($voted_questions as $voted_question)
		if (array_key_exists($voted_question, $questions))
			unset($questions[$voted_question]);
		return $questions;
	}

	function getVotedQuestions()
	{
		$cookie = \App()->Cookie->getCookie('voted_questions');
		if ($cookie)
			return unserialize($cookie);
		else
			return array();
	}

	function setQuestionInactive($question_id)
	{
		$voted_questions = $this->getVotedQuestions();
		$voted_questions[$question_id] = (int)$question_id;
		return $this->saveVotedQuestions($voted_questions);
	}

	function saveVotedQuestions($voted_questions)
	{
		$serialized_voted_questions = serialize($voted_questions);
		return \App()->Cookie->setCookie('voted_questions', $serialized_voted_questions, 365);
	}

	function increaseAnswerCounter($answer_id)
	{
		return \App()->DB->query("UPDATE `poll_answers` SET `counter`=`counter`+1 WHERE `id`=?n", $answer_id);
	}

	function isQuestionDisplayed($question)
	{
		return $question['display'];
	}
}

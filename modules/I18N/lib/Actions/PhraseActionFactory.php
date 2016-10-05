<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\lib\Actions;

class PhraseActionFactory implements \core\IService
{
	public function init()
	{
	}

	function get($action, $params, &$template_processor)
	{
		$i18n =\App()->I18N;
		$storage = \App()->Session;
		
		switch ($action)
		{
			case "search_phrases":
				
				$searchPhraseAction = new SearchPhraseAction($i18n, $params, $template_processor);
				$storePhraseSearchCriteriaAction = new StorePhraseSearchCriteriaAction($storage, $params);
				
				$phraseAction = new SerialActionBatch();
				$phraseAction->addAction($searchPhraseAction);
				$phraseAction->addAction($storePhraseSearchCriteriaAction);
				break;
				
			case "remember_previous_state":
				
				$restorePhraseSearchCriteriaAction = new RestorePhraseSearchCriteriaAction($storage);
				$restorePhraseSearchCriteriaAction->perform();
				$phraseAction = new SearchPhraseAction($i18n, $restorePhraseSearchCriteriaAction->getCriteria(), $template_processor);

				break;
				
			case "add_phrase":
				$phraseAction = new SerialActionBatch();
				$phraseAction->addAction(new AddPhraseAction($i18n, $params));
				$phraseAction->addAction(
					new StorePhraseSearchCriteriaAction(
						$storage,
						array(
							'phrase_id' => $params['phrase'],
							'domain' => null,
						)
					)
				);
				
				break;
				
			case "update_phrase":
				$phraseAction = new UpdatePhraseAction($i18n, $params);
				break;
				
			case "delete_phrase": 
				
				$phrase = isset($params['phrase']) ? $params['phrase'] : null;
				$domain = isset($params['domain']) ? $params['domain'] : null;
				
				// see remember_previous_state
				$criteria = null;
				$searchPhraseAction = new SearchPhraseAction($i18n, $criteria, $template_processor);
				$restorePhraseSearchCriteriaAction = new RestorePhraseSearchCriteriaAction($storage, $criteria);
				$deletePhraseAction = new DeletePhraseAction($i18n, $phrase, $domain);
				
				$phraseAction = new SerialActionBatch();
				if ($deletePhraseAction->canPerform())
				{
					$phraseAction->addAction($deletePhraseAction);
				}
				$phraseAction->addAction($restorePhraseSearchCriteriaAction);
				$phraseAction->addAction($searchPhraseAction);
				break;
				
			default:
				$phraseAction = new PhraseAction();
				break;
		}
		
		return $phraseAction;
	}
}

?>

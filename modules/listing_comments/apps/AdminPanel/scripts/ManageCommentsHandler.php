<?php
/**
 *
 *    Module: listing_comments v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_comments-7.5.0-1
 *    Tag: tags/7.5.0-1@19790, 2016-06-17 13:19:43
 *
 *    This file is part of the 'listing_comments' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_comments\apps\AdminPanel\scripts;

class ManageCommentsHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\classifieds\apps\AdminPanel\IMenuItem
{
	protected $displayName	= 'Manage Listing Comments';
	protected $moduleName = 'listing_comments';
	protected $functionName = 'manage_comments';

	private $search = null;
	private $modelListingComment = null;
	const DEFAULT_ITEMS_PER_PAGE = 10;

	public function respond()
	{
		$this->showForm();
		$this->showSearchResults();
		$this->saveSearchToSession($this->getSearch());
	}

	private function showForm()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$searchFormBuilder = new \lib\ORM\SearchEngine\SearchFormBuilder($this->getModelListingComment());
		$requestReflection = \App()->ObjectMother->createReflectionFactory()->createHashtableReflector($this->getSearch()->getRequest());
		$searchFormBuilder->setRequestData($requestReflection);
		$searchFormBuilder->registerTags($templateProcessor);
		$categoryTree = \App()->CategoryTree;
		$rootNode = $categoryTree->getNode(\App()->CategoryManager->getRootId());
		$templateProcessor->assign('current_category', $this->getCategorySid());
		$templateProcessor->assign('categories', $rootNode->toArray() );
		$templateProcessor->display("search_comments_form.tpl");
	}

	private function showSearchResults()
	{
		if (!empty($_REQUEST['action']) || !empty($_REQUEST['restore']))
		{
			$templateProcessor = \App()->getTemplateProcessor();

			$listingsSid = array();
			foreach ($this->getSearch()->getFoundObjectCollection() as $comment)
			{
				$listingsSid[] = $comment->getListingSid();
			}
			$listingsSid = array_unique($listingsSid);

			$comments = new \lib\ORM\SearchEngine\SearchToObjectsCollectionAdapter();
			$comments->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
			$comments->setSearch($this->getSearch());

			$templateProcessor->assign('commentsSidToDisplay', $this->getSearch()->getFoundObjectSidCollection());
			$templateProcessor->assign('listings', $this->getListingsAndAssignSearch($listingsSid, $templateProcessor));
			$templateProcessor->assign('comments', $comments);
			$templateProcessor->assign('selectedCommets', isset($_REQUEST['selectedCommets']) ? $_REQUEST['selectedCommets'] : null);
			$templateProcessor->assign('search', new \lib\ORM\SearchEngine\SearchArrayAdapter($this->getSearch()));
			$templateProcessor->display("manage_comments.tpl");
		}
	}

	private function getListingsAndAssignSearch($listingsSid, &$templateProcessor)
	{
		if (empty($listingsSid)) return array();

		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore')
		{
			$search = unserialize(\App()->Session->getContainer('SEARCHES_LISTINGS')->getValue($_REQUEST['searchId']));
		}
		else
		{
			$search = new \lib\ORM\SearchEngine\Search();
			$search->setId($this->getSearch()->getId());
			$search->setPage(1);
			$search->setObjectsPerPage(self::DEFAULT_ITEMS_PER_PAGE);
		}
		$search->setRequest(array('sid' => array('in' => $listingsSid)));

		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
		$search->setModelObject(\App()->ListingFactory->getListing(array(), 0));
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		if (isset($_REQUEST['page'])) $search->setPage(intval($_REQUEST['page']));
		if (isset($_REQUEST['items_per_page'])) $search->setObjectsPerPage(intval($_REQUEST['items_per_page']));

		\App()->Session->getContainer('SEARCHES_LISTINGS')->setValue($search->getId(), serialize($search));

		$listings = new \lib\ORM\SearchEngine\SearchToObjectsCollectionAdapter();
		$listings->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
		$listings->setSearch($search);

		$templateProcessor->assign('listing_search', new \lib\ORM\SearchEngine\SearchArrayAdapter($search));

		return $listings;
	}

	private function getSearch()
	{
		if (is_null($this->search))
		{
						if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore')
			{
				$this->search = $this->getSearchFromSession($_REQUEST['searchId']);
				$requestFromSearch = $this->search->getRequest();
				unset($requestFromSearch['message']);
				$_REQUEST = array_merge($requestFromSearch, $_REQUEST);
			}
			else
			{
				$this->search = new \lib\ORM\SearchEngine\Search();
				$this->search->setId($this->generateSearchId());
				$this->search->setPage(1);
				$this->search->setObjectsPerPage(null);
				$this->search->setSearchFormUri(\App()->PageManager->getRequestedUri());
				$this->search->setSearchResultsUri(\App()->Navigator->getURI());
				$this->search->setSortingFields(array('posted'=>'DESC'));
			}
			$this->search->setRequest($_REQUEST);
			$this->search->setDB(\App()->DB);

			$rowMapper = new \modules\listing_comments\lib\ListingCommentRowMapperAdapter(\App()->ListingCommentManager, \App()->UserManager, \App()->ListingManager);
			$rowMapper->setListingFieldManager(\App()->ListingFieldManager);
			$rowMapper->setRatingManager(\App()->ObjectMother->createRatingManager('listing'));
			$this->search->setRowMapper($rowMapper);
			$this->search->setModelObject($this->getModelListingComment());
			$this->search->setCriterionFactory(\App()->SearchCriterionFactory);
		}
		return $this->search;
	}

	private function getModelListingComment()
	{
		if (is_null($this->modelListingComment)) $this->modelListingComment = \App()->ListingCommentManager->createListingComment();
		$this->modelListingComment->addUsernameProperty();
		$this->modelListingComment->addListingIDProperty();
		$categoryTreeAdapterForORM = new \modules\classifieds\lib\Category\CategoryTreeAdapterForORM(\App()->CategoryTree);
		$this->modelListingComment->addProperty(
			array(
				'id' => 'category_sid',
				'type' => 'tree',
				'value' => null,
				'is_system' => true,
				'table_name' => 'classifieds_listings',
				'column_name' => 'category_sid',
				'join_condition' => array('key_column' => 'listing_sid', 'foriegn_column' => 'sid'),
				'tree_values' => $categoryTreeAdapterForORM->getTreeStructure()
			)
		);
		return $this->modelListingComment;
	}

	private function getCategorySid()
	{
		if (isset($_REQUEST['category_sid']) )
		{
			if (is_array($_REQUEST['category_sid']))
			{
				$categorySid = end($_REQUEST['category_sid']['tree']);
			}
			else
			{
				$categorySid = $_REQUEST['category_sid'];
			}
		}
		else
		{
			$categorySid = \App()->CategoryManager->getRootId();
		}
		return $categorySid;
	}
	private function generateSearchId()
	{
		return uniqid();
	}
	private function getSearchFromSession($searchId)
	{
		$ss = \App()->Session->getContainer('SEARCHES')->getValue($searchId);
		return unserialize($ss);
	}
	private function saveSearchToSession($search)
	{
		\App()->Session->getContainer('SEARCHES')->setValue($search->getId(), serialize($search));
	}

	public static function getOrder()
	{
		return 400;
	}

	public function getCaption()
	{
		return "Manage Comments";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array
		(
		);
	}
}

<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM\SearchEngine;
class Search
{
    private $with = [];
	// dependencies //
	private $DB;
	public function setDB($db){ $this->DB = $db; }

	private $rowMapper;
	public function setRowMapper($m){$this->rowMapper = $m; } // must implement ->mapRowToObject($hash) --- method that maps a database row to an object

	private $CriteriaFactory;
	public function setCriterionFactory($f){$this->CriteriaFactory = $f;}

	/**
	 * @var \lib\ORM\Object
	 */
	private $modelObject;
	public function setModelObject($m){$this->modelObject = $m;}

	// data //
	private $sortingFields = array();
	private $pageNumber = 1;
	private $listingsPerPage = 10;
	private $requestData = array();
	private $searchFormUri = null;
	private $searchResultsUri = null;
	private $id = null;
	private $resultViewType = null;

	private $totalNumberOfObjectsFound = null;
	private $sqlTranslator = null;
    private $numberOfPage = null;
    private $objectSid = null;

    /**
     * @return null
     */
    public function getObjectSid()
    {
        return $this->objectSid;
    }

    /**
     * @param null $objectSid
     */
    public function setObjectSid($objectSid)
    {
        $this->objectSid = $objectSid;
    }
    /**
     * @var array
     */
    private $neighborSids = null;

	public function __construct()
	{
		$this->setCriterionFactory(\App()->SearchCriterionFactory);
	}

	public function setRequest($requestArray){ $this->requestData = $requestArray; }
	public function getRequest(){ return $this->requestData; }

	public function setPage($pageNumber){ $this->pageNumber = $pageNumber;}
	public function getPage(){ return $this->pageNumber;}

	public function setId($id){ $this->id = $id;}
	public function getId(){ return $this->id;}

	public function setSearchFormUri($searchFormUri){$this->searchFormUri = $searchFormUri;}
	public function getSearchFormUri(){return $this->searchFormUri; }

	public function setSearchResultsUri($uri){$this->searchResultsUri = $uri;}
	public function getSearchResultsUri(){return $this->searchResultsUri; }

	public function setObjectsPerPage($listingsPerPage)
	{
		if (is_numeric($listingsPerPage) && $listingsPerPage > 0)
		{
			$this->listingsPerPage = (int) $listingsPerPage;
		}
	}
	public function getObjectsPerPage(){return $this->listingsPerPage; }

	public function setSortingFields(array $sortingFields){$this->sortingFields = $sortingFields;}
	public function getSortingFields(){return $this->sortingFields; }

	public function getNumberOfPages()
	{
        if (is_null($this->numberOfPage))
        {
            $this->numberOfPage = ceil( $this->getNumberOfObjectsFound() / $this->listingsPerPage );
        }
        return $this->numberOfPage;
	}

	public function getNumberOfObjectsFound()
	{
		if (is_null($this->totalNumberOfObjectsFound))
		{
			$this->totalNumberOfObjectsFound = $this->getTotalNumberOfObjectsFound();
		}
		return $this->totalNumberOfObjectsFound;
	}

	public function getFoundObjectSidCollection($limit = null)
	{
		$translator = $this->getSQLTranslator();
		$translator->setLimit($limit);
		$sqlStatement = $translator->getSidsSqlStatement();
		$res = $this->DB->query($sqlStatement);
		$sidCollection = array_map(create_function('$row', 'return $row["sid"];'), $res);
		return $sidCollection;
	}

    private function getFoundCollection()
    {
        $this->normalizePageNumber();
        $translator = $this->getSQLTranslator();
        $translator->setLimit($this->listingsPerPage);
        $translator->setOffset(($this->pageNumber - 1) * $this->listingsPerPage);
        $sqlStatement = $translator->getSearchSqlStatement();
        return $this->DB->query($sqlStatement);
    }

    public function getFoundObjectCollection()
    {
        $array = $this->getFoundCollection();
        \App()->MemoryCache->set('Collection_' . get_class($this->modelObject), $array);
        $collection = new ArrayToIterableCollectionAdapter($array);

        if (!empty($array))
        {
            $withData = [];
            foreach($this->with as $with)
            {
                $withData[$with] = $this->modelObject->{$with}();
            }
            $collection->setRelationData($withData);
        }
        $collection->setRowMapper($this->rowMapper);

        return $collection;
    }

	private function normalizePageNumber()
	{
        if ($this->pageNumber !== 1)
        {
            $this->pageNumber = min($this->pageNumber, $this->getNumberOfPages());
            $this->pageNumber = max($this->pageNumber, 1);
        }
	}


	private function buildSortingFieldsForSQLTranslator()
	{
		$result = array();
		$knownProperties = $this->modelObject->getDetails()->getProperties();
		foreach ($this->sortingFields as $propertyId => $direction) {
			$result[$propertyId] = array
			(
				'direction' => ($direction == 'DESC') ? 'DESC' : 'ASC',
				'property' => $knownProperties[$propertyId]
			);
		}

		return $result;
	}

	private function getTotalNumberOfObjectsFound()
	{
		$translator = $this->getSQLTranslator();
		$sqlStatement = $translator->getCountSqlStatement();
		return $this->DB->getSingleValue($sqlStatement);
	}

	public function getNumberOfObjectsFoundGroupedBy($columnName)
	{
		$translator = $this->getSQLTranslator();
		$sqlStatement = $translator->getCountGroupedBySqlStatement($columnName);
		return $this->DB->query($sqlStatement);
	}

	private function getSQLTranslator()
	{
		if (is_null($this->sqlTranslator))
		{
			$this->sqlTranslator = new SearchSqlTranslator();
			$this->sqlTranslator->setTargetTableName($this->modelObject->getDetails()->getTableName());
			$this->sqlTranslator->setTargetTableAlias($this->modelObject->getDetails()->getTableAlias());
			$this->sqlTranslator->setCriteria($this->getCriteria());
			$this->sqlTranslator->setSortingFields($this->buildSortingFieldsForSQLTranslator());
		}
		return $this->sqlTranslator;
	}

	private function getCriteria()
	{
		$criteria = array();
		$knownProperties = $this->modelObject->getDetails()->getProperties();
		foreach (array_keys($knownProperties) as $propertyName)
		{
			if (isset($this->requestData[$propertyName]))
			{
				$criteriaData =$this->requestData[$propertyName];
				if (is_array($criteriaData))
				{
					foreach ($criteriaData as $criteriaType => $criteriaValue)
					{
						$criterion = $this->CriteriaFactory->getCriterionByType($criteriaType);
						$criterion->setProperty($knownProperties[$propertyName]);
						$criterion->setValue($criteriaValue);
						array_push($criteria, $criterion);
					}
				}
			}
		}
		return $criteria;
	}

	public function __sleep()
	{
		return array('searchFormUri', 'searchResultsUri', 'id', 'sortingFields', 'pageNumber', 'listingsPerPage', 'requestData', 'resultViewType');
	}

	public function isSortable($fieldId)
	{
		$knownProperties = $this->modelObject->getDetails()->getProperties();
		return in_array($fieldId, array_keys($knownProperties));
	}

	public function setResultViewType($type)
	{
		$this->resultViewType = $type;
	}

	public function getResultViewType()
	{
		return $this->resultViewType;
	}

	static public function createSearch($requestData, $model)
	{
		$search = new Search();
		$search->setRequest($requestData);
		$search->setModelObject($model);
		$search->setDB(\App()->DB);
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		return $search;
	}

    /**
     * @return array
     */
    public function getNeighborSids()
    {
        if (is_null($this->objectSid))
        {
            throw new \InvalidArgumentException('Object sid is null');
        }

        $objectSid = $this->objectSid;

        if (is_null($this->neighborSids))
        {
            $this->normalizePageNumber();
            $translator = $this->getSQLTranslator();
            $translator->setLimit($this->listingsPerPage);
            $translator->setOffset(($this->pageNumber - 1) * $this->listingsPerPage);
            $sqlStatement = $translator->getSidsSqlStatement();
            $sids = $this->DB->column($sqlStatement);
            $pos = array_search($objectSid, $sids);
            $prev = isset($sids[$pos - 1]) ? $sids[$pos - 1] : null;
            $next = isset($sids[$pos + 1]) ? $sids[$pos + 1] : null;

            if (is_null($next) && $this->pageNumber < $this->getNumberOfPages())
            {
                $translator->setLimit(1);
                $translator->setOffset($this->pageNumber * $this->listingsPerPage);
                $next = $this->DB->getSingleValue($translator->getSidsSqlStatement());
            }

            if(is_null($prev) && $this->pageNumber > 1)
            {
                $translator->setLimit($this->listingsPerPage);
                $translator->setOffset(($this->pageNumber -2) * $this->listingsPerPage);
                $tmpSids = $this->DB->column($translator->getSidsSqlStatement());
                $prev = array_pop($tmpSids);
            }
            $this->neighborSids = ['prev' => $prev, 'next' => $next];
        }

        return $this->neighborSids;
    }

    public function setWith($with)
    {
        $this->with = $with;
    }
}

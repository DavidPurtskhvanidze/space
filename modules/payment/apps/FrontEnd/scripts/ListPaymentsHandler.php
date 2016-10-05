<?php
/**
 *
 *    Module: payment v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: payment-7.5.0-1
 *    Tag: tags/7.5.0-1@19802, 2016-06-17 13:20:16
 *
 *    This file is part of the 'payment' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment\apps\FrontEnd\scripts;

class ListPaymentsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Payments';
	protected $moduleName = 'payment';
	protected $functionName = 'payments';

	private $templateProcessor;
	private $model;
	private static $DEFAULT_RECORDS_PER_PAGE = 1000;
	private static $CurrentSearchId = 'payments_search';
	
	public function respond()
	{
		if (!\App()->UserManager->isUserLoggedIn())
		{
			\App()->UserManager->requireLogin();
			return;
		}

		$this->templateProcessor = \App()->getTemplateProcessor();

		$paymentId = isset($_REQUEST['paymentId']) ? $_REQUEST['paymentId'] : null;
        $action = isset($_REQUEST['action']) ? strtolower($_REQUEST['action']) : null;

        switch($action)
		{
			case 'complete':
					$payment = \App()->PaymentManager->getObjectBySID($paymentId);
					$payment->restart();
					\App()->PaymentManager->savePayment($payment);
					\App()->PaymentActionsFactory->createRedirectToPaymentPageAction($payment)->perform();
				break;
			case 'delete':
					\App()->PaymentManager->deletePayment($paymentId);
					\App()->SuccessMessages->addMessage('TRANSACTION_WAS_DELETED', array('paymentId' => $paymentId));
					throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI());
				break;
		}

		$_REQUEST['username']['equal'] = \App()->UserManager->getCurrentUser()->getUsername();
		if (!isset($_REQUEST['creation_date']))
		{
			if (!isset($_REQUEST['action']) or $_REQUEST['action'] != 'restore')
			{
				$i18n = \App()->I18N;
				$_REQUEST['creation_date']['not_earlier'] = $i18n->getDate(date('Y-m-d', time() - 30*24*60*60));
				$_REQUEST['creation_date']['not_later'] = $i18n->getDate(date('Y-m-d'));
			}
		}
		$this->registerFormTags();
				$search = $this->getSearch();
        $this->setSortingFields($search);
		$this->templateProcessor->assign("search", new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
		$this->templateProcessor->assign("payments", $this->getPayments($search));
		$this->templateProcessor->display("payments.tpl");
		$this->saveSearchToSession($search);
	}
	private function saveSearchToSession($search)
	{
		$ss = serialize($search);
		\App()->Session->setValue(self::$CurrentSearchId, $ss);
	}
	private function getModel()
	{
		if (is_null($this->model))
		{
			$this->model = \App()->PaymentFactory->createPayment(array());
			$this->model->addProperty
			(
				array
				(
					'id' => 'username',
					'type' => 'string',
					'value' => '',
					'table_name' => 'users_users',
					'column_name' => 'username',
					'join_condition' => array('key_column' => 'user_sid', 'foriegn_column'=> 'sid'),
				)
			);
			$this->model->addProperty
			(
				array
				(
					'id' => 'sid',
					'type' => 'string',
					'value' => '',
				)
			);
			$this->model->addProperty
			(
				array
				(
					'id' => 'id',
					'type' => 'string',
					'value' => '',
					'column_name' => 'sid',
				)
			);
		}
		return $this->model;
	}
	private function getPayments($search)
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}
	private function getSearch()
	{
						if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore')
		{
			$search = unserialize(\App()->Session->getValue(self::$CurrentSearchId));
			$search->setRequest(array_merge($search->getRequest(), $_REQUEST)); // i need to incorporate new parameters, including sorting fields and order
		}
		else
		{
			$search = new \lib\ORM\SearchEngine\Search();
			$search->setRequest($_REQUEST);
			$search->setPage(1);
			$search->setObjectsPerPage(self::$DEFAULT_RECORDS_PER_PAGE);
		}
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\payment\lib\Payment\PaymentFactoryToRowMapperAdapter(\App()->PaymentFactory, \App()->UserManager));
		$search->setModelObject($this->getModel());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		return $search;
	}
	
	private function registerFormTags()
	{
				$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($this->getModel());
		$search_form_builder->setRequestData(\App()->ObjectMother->createReflectionFactory()->createHashtableReflector($this->getSearch()->getRequest()));
		$search_form_builder->registerTags($this->templateProcessor);
	}

    private function setSortingFields($search)
    {
		if (isset($_REQUEST['sorting_fields']) && is_array($_REQUEST['sorting_fields']))
            $search->setSortingFields($_REQUEST['sorting_fields']);
    }
}

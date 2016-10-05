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


namespace modules\payment\apps\AdminPanel\scripts;

class ListPaymentsHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\payment\apps\AdminPanel\IMenuItem
{
	private $search;
	protected $moduleName = 'payment';
	protected $functionName = 'payments';

	/**
	 * @var \modules\smarty_based_template_processor\lib\TemplateProcessor
	 */
	private $templateProcessor;
	private $model;
	private static $DEFAULT_RECORDS_PER_PAGE = 10;
	private static $CurrentSearchId = 'payments_search';
	
	public function respond()
	{
		$this->templateProcessor = \App()->getTemplateProcessor();
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'viewCallbackData')
		{
			$this->displayCallBackData();
			return;
		}
		if (isset($_REQUEST['action']) && strcasecmp($_REQUEST['action'], 'endorse') == 0)
		{
			$this->performEndorseAction();
			$_REQUEST['action'] = 'restore';
		}
		if (isset($_REQUEST['action']) && strcasecmp($_REQUEST['action'], 'delete') == 0)
		{
			$payments_sids = $_REQUEST['payments'];
			foreach (array_keys($payments_sids) as $payment_sid)
			{
				\App()->PaymentManager->deletePayment($payment_sid);
				\App()->SuccessMessages->addMessage('TRANSACTION_WAS_DELETED', array('paymentId' => $payment_sid));
			}
			throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI());
		}
		if (!isset($_REQUEST['creation_date']))
		{
			if (!isset($_REQUEST['action']) or $_REQUEST['action'] != 'restore')
			{
				$I18N = \App()->I18N;
				$_REQUEST['creation_date']['not_earlier'] = $I18N->getDate(date('Y-m-d', time() - 30*24*60*60));
				$_REQUEST['creation_date']['not_later'] = $I18N->getDate(date('Y-m-d'));
			}
		}
		$this->displayForm();
		$this->displayTable();
	}
	private function performEndorseAction()
	{
		$payments_sids = $_REQUEST['payments'];
		foreach (array_keys($payments_sids) as $payment_sid)
		{
			/**
			 * @var \modules\payment\lib\PaymentMethodMoney $paymentMethodMoney
			 */
			$paymentMethodMoney = \App()->PaymentSystemManager->getPaymentMethodByClassName('modules\payment\lib\PaymentMethodMoney');
			if (null === \App()->PaymentManager->getObjectBySID($payment_sid))
			{
				\App()->ErrorMessages->addMessage('PAYMENT_NOT_FOUND', array('payment_sid' => $payment_sid), 'payment');
			}
			else
			{
				try
				{
					$paymentMethodMoney->endorsePayment($payment_sid);
				}
				catch(\modules\payment_system\lib\Exception $e)
				{
					\App()->ErrorMessages->addMessage('INVOICE_ALREADY_PROCESSED', array('payment_sid' => $payment_sid), 'payment');
				}
			}
		}
		throw new \lib\Http\RedirectException($this->getUrl() . '?action=restore');
	}
	private function displayTable()
	{
		$search = $this->getSearch();
		$this->setObjectsPerPage($search);
		$this->setPage($search);
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
					'autocomplete_service_name' => 'UserManager',
					'autocomplete_method_name' => 'Username'
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
			
			$paymentGatewayOptions = array();
			$paymentGatewayIds = \App()->PaymentManager->getAllPaymentGatewayIds();
			foreach ($paymentGatewayIds as $paymentGatewayId)
			{
				$paymentGatewayOptions[] = array(
					'id' => $paymentGatewayId,
					'caption' => \App()->PaymentGatewayManager->getPaymentGatewayCaptionById($paymentGatewayId),
				);
			}
			$this->model->addProperty
			(
				array
				(
					'id'		=> 'payment_gateway',
					'caption'	=> 'Payment Gateway', 
					'type'		=> 'list',
					'column_name' => 'payment_gateway_id',
					'list_values' => $paymentGatewayOptions,
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
		if (is_null($this->search))
		{
			if (\App()->Request['action'] == 'restore')
			{
				$searchInSession = \App()->Session->getValue(self::$CurrentSearchId);
				if (!is_null($searchInSession))
				{
					$this->search = unserialize($searchInSession);
					$this->search->setRequest(array_merge($this->search->getRequest(), $_REQUEST)); // i need to incorporate new parameters, including sorting fields and order
				}
				else
				{
					\App()->ErrorMessages->addMessage("SEARCH_EXPIRED");
				}
			}
			if (is_null($this->search))
			{
				$this->search = new \lib\ORM\SearchEngine\Search();
				$this->search->setRequest($_REQUEST);
				$this->search->setPage(1);
				$this->search->setObjectsPerPage(self::$DEFAULT_RECORDS_PER_PAGE);
			}
			$this->search->setDB(\App()->DB);
			$this->search->setRowMapper(new \modules\payment\lib\Payment\PaymentFactoryToRowMapperAdapter(\App()->PaymentFactory, \App()->UserManager));
			$this->search->setModelObject($this->getModel());
			$this->search->setCriterionFactory(\App()->SearchCriterionFactory);
		}
		return $this->search;
	}

	private function displayForm()
	{
				$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($this->getModel());
		$search_form_builder->setRequestData(\App()->ObjectMother->createReflectionFactory()->createHashtableReflector($this->getSearch()->getRequest()));
		$search_form_builder->registerTags($this->templateProcessor);
		$this->templateProcessor->display("payment_form.tpl");
	}

	private function displayCallBackData()
	{
		if ( isset($_REQUEST['payment']))
		{
			/** @var $payment Payment */
			$payment = \App()->PaymentManager->getObjectBySID($_REQUEST['payment']);
			if (is_null($payment))
			{
				\App()->ErrorMessages->addMessage('PAYMENT_NOT_FOUND');
			}
			else
			{
				$this->templateProcessor->assign('paymentSid', $payment->getSID());
				$this->templateProcessor->assign('callbackData', print_r($payment->getCallbackData(),true));
				
				$paymentGateway = \App()->PaymentGatewayManager->getPaymentGatewayById($payment->getPaymentGatewayId());
				if ($paymentGateway)
				{
					$this->templateProcessor->assign(
						'userFriendlyTransactionData',
						$paymentGateway->getUserFriendlyTransactionDataFromCallBackData($payment->getCallbackData())
					);
				}
			}
			
			$this->templateProcessor->display('display_callback_data.tpl');
		}
	}
    
    private function setSortingFields($search)
    {
		if (isset($_REQUEST['sorting_fields']) && is_array($_REQUEST['sorting_fields']))
            $search->setSortingFields($_REQUEST['sorting_fields']);
    }

	private function setPage($search)
	{
		if (isset($_REQUEST['page'])) $search->setPage(intval($_REQUEST['page']));
	}
	private function setObjectsPerPage($search)
	{
		if (isset($_REQUEST['items_per_page'])) $search->setObjectsPerPage(intval($_REQUEST['items_per_page']));
	}

	public function getCaption()
	{
		return "Payments";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
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

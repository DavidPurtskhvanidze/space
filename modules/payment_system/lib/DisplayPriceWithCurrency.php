<?php
/**
 *
 *    Module: payment_system v.7.4.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_system-7.4.0-1
 *    Tag: tags/7.4.0-1@19076, 2015-12-14 12:49:32
 *
 *    This file is part of the 'payment_system' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_system\lib;

class DisplayPriceWithCurrency implements \modules\smarty_based_template_processor\lib\IPlugin
{
	public function getPluginType()
	{
		return 'function';
	}

	public function getPluginTag()
	{
		return 'display_price_with_currency';
	}

	public function getPluginCallback()
	{
		return array($this, 'display');
	}

	/**
	 * @param array $params
	 * @param \Smarty_Internal_Template $smartyInternalTemplate
	 * @return mixed
	 */
	public function display($params, $smartyInternalTemplate)
	{
		/**
		 * @var \modules\smarty_based_template_processor\lib\TemplateProcessor $templateProcessor
		 */
		$templateProcessor = $smartyInternalTemplate->smarty;
		if (!empty($params['payment_method']))
		{
			$paymentMethod = \App()->PaymentSystemManager->getPaymentMethodByClassName($params['payment_method']);
		}
		else
		{
			$paymentMethod = \App()->PaymentSystemManager->getCurrentPaymentMethod();
		}
		$priceWithCurrency = $paymentMethod->displayPriceWithCurrency($params['amount'], $templateProcessor);
		if (isset($params['assign']))
		{
			$smartyInternalTemplate->assign($params['assign'], $priceWithCurrency);
		}
		else
		{
			return $priceWithCurrency;
		}
	}
}

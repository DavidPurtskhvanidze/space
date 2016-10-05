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

class PaymentSystemManager implements \core\IService
{
	const DEFAULT_PAYMENT_METHOD = 'modules\payment\lib\PaymentMethodMoney';

	private $paymentMethods = array();

	public function init()
	{
		$paymentMethodsImplementations = new \core\ExtensionPoint('modules\payment_system\lib\IPaymentMethod');
		foreach ($paymentMethodsImplementations as $paymentMethod)
		{
			/**
			 * @var IPaymentMethod $paymentMethod
			 */
			$this->paymentMethods[get_class($paymentMethod)] = $paymentMethod->getCaption();
		}
	}

	/**
	 * @return IPaymentMethod
	 */
	public function getCurrentPaymentMethod()
	{
		$paymentMethodClassName = \App()->SettingsFromDB->getSettingByName('payment_method');
		if (!$this->isPaymentMethodAvailable($paymentMethodClassName))
		{
			$paymentMethodClassName = self::DEFAULT_PAYMENT_METHOD;
		}
		return $this->getPaymentMethodByClassName($paymentMethodClassName);
	}

	/**
	 * @param string $className
	 * @return IPaymentMethod
	 */
	public function getPaymentMethodByClassName($className)
	{
		/**
		 * @var IPaymentMethod $paymentMethod
		 */
		$paymentMethod = new $className;
		$paymentMethod->init();
		return $paymentMethod;
	}

	private function isPaymentMethodAvailable($paymentMethodClassName)
	{
		return isset($this->paymentMethods[$paymentMethodClassName]);
	}

	public function getPaymentMethodSettingName()
	{
		return 'payment_method';
	}
}

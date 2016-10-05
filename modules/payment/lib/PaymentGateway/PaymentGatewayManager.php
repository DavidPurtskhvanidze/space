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


namespace modules\payment\lib\PaymentGateway;

class PaymentGatewayManager extends \lib\ORM\ObjectManager implements \core\IService
{
	var $db_table_name = 'payment_gateways';
	var $object_name = 'PaymentGateway';
	
	public function getPaymentGateways()
	{
		return new \core\ExtensionPoint('modules\payment\lib\PaymentGateway\IPaymentGateway');
	}
	/**
	 * @param string $id
	 * @return IPaymentGateway
	 * @throws PaymentGatewayNotFoundException
	 */
	public function getPaymentGatewayById($id)
	{
		$gateways = $this->getPaymentGateways();
		foreach ($gateways as $gateway)
		{
			if ($gateway->getId() == $id)
			{
				return $gateway;
			}
		}
		throw new PaymentGatewayNotFoundException($id);
	}

	public function init()
	{
		$this->dbManager = new \lib\ORM\ObjectDBManager();
	}

	public function saveGateway($gateway)
	{
		$commonPropertiesIds = array('id', 'caption', 'active');
		$extraProperties = array();
		foreach ($gateway->getDetails()->getProperties() as $id => $property)
		{
			if (!in_array($id, $commonPropertiesIds))
			{
				$extraProperties[$id] = $property->getValue();
				$gateway->deleteProperty($id);
			}
		}

		$gateway->addProperty
		(
			array
			(
				'id' => 'serialized_extra_info',
				'type' => 'text',
				'value' => serialize($extraProperties),
			)
		);
		parent::saveObject($gateway);
	}
	
	public function getInfoById($gatewayId)
	{
		$info = \App()->DB->getSingleRow("SELECT * FROM `payment_gateways` WHERE `id` = ?s", $gatewayId);
		if (empty($info)) return array();
		if (($extraInfo = unserialize($info['serialized_extra_info'])) !== false)
		{
			$info = array_merge($extraInfo, $info);
		}
		return $info;
	}

	public function getPaymentGatewaysCount()
	{
		return iterator_count($this->getPaymentGateways());
	}
	
	public function getPaymentGatewayCaptionById($paymentGatewayId)
	{
		static $paymentGatewayCaptions = array();
		if (!isset($paymentGatewayCaptions[$paymentGatewayId]))
		{
			try 
			{
				$paymentGatewayCaptions[$paymentGatewayId] = $this->getPaymentGatewayById($paymentGatewayId)->getCaption();
			}
			catch (\modules\payment\lib\PaymentGateway\PaymentGatewayNotFoundException $e)
			{
				$paymentGatewayCaptions[$paymentGatewayId] = $paymentGatewayId;
			}
		}
		
		return $paymentGatewayCaptions[$paymentGatewayId];
	}
}

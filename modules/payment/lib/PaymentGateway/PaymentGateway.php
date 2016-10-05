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

abstract class PaymentGateway implements IPaymentGateway
{
	protected $id;
	protected $sid;
	protected $caption;
	protected $hasDataToStore = true;

	protected $info;
	protected $errors = array();

	public function __construct()
	{
		$this->info = \App()->PaymentGatewayManager->getInfoById($this->getId());
		if (isset($this->info['sid'])) $this->sid = $this->info['sid'];
	}

	public function getId()
	{
		return $this->id;
	}
	public function getSid()
	{
		return $this->sid;
	}
	public function getCaption()
	{
		return $this->caption;
	}
	public function getDetailsInfo()
	{
		return array
		(
			array
			(
				'id' => 'id',
				'caption' => 'ID',
				'type' => 'unique_string',
				'length' => '20',
				'is_required' => true,
				'is_system' => true,
				'value' => $this->getId(),
			),
			array
			(
				'id' => 'caption',
				'caption' => 'Caption',
				'type' => 'string',
				'length' => '20',
				'is_required' => true,
				'is_system' => true,
				'save_into_db' => false,
				'value' => $this->getCaption(),
			),
		);
	}

	public function getInfo()
	{
		return $this->info;
	}

	public function updateInfo($info)
	{
		$info = array_intersect_key($info, $this->info);
		$this->info = array_merge($this->info, $info);
	}

	public function getHasDataToStore()
	{
		return $this->hasDataToStore;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function displayAdditionalInfo()
	{
	}
	
	public function getUserFriendlyTransactionDataFromCallBackData($callbackData)
	{
		return array();
	}
}

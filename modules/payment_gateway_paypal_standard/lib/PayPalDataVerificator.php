<?php
/**
 *
 *    Module: payment_gateway_paypal_standard v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_gateway_paypal_standard-7.3.0-1
 *    Tag: tags/7.3.0-1@18552, 2015-08-24 13:37:38
 *
 *    This file is part of the 'payment_gateway_paypal_standard' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_gateway_paypal_standard\lib;

class PayPalDataVerificator
{
	public $timeOut = 30;
	public $timeLimit = null;
	public $response;

	public function __construct($paypalUrl)
	{
		$this->paypalUrl = $paypalUrl;
	}

	private function getPlainRequestData()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') return file_get_contents('php://input');
		return $_SERVER['QUERY_STRING'];
	}

	public function dataIsVerified()
	{
		$postData ='cmd=_notify-validate&' . $this->getPlainRequestData();
		$this->response = $this->postDataWithCUrl($postData);
		if ($this->response == "VERIFIED") return true;
		return false;
	}

	private function postDataWithSslStream($data)
	{
		$result = null;
		$x = parse_url($this->paypalUrl);
		$streamURL = 'ssl://' . $x['host'];
		$header = 'POST ' . $x['path'] . " HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($data) . "\r\n\r\n";
		$fp = fsockopen ($streamURL, 443, $errno, $errstr, 30);
		if (!$fp) return false; 
		fputs ($fp, $header . $data);
		while (!feof($fp)) $result = fgets($fp, 1024);
		fclose ($fp);
		return $result;
	}

	private function postDataWithCUrl($data)
	{
		$curl = curl_init($this->paypalUrl);
		if ($this->timeLimit > 0) @set_time_limit($this->timeLimit);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeOut);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	public function getTimeOut() { return $this->timeOut; }

	public function setTimeOut($timeOut) { $this->timeOut = $timeOut; }

	public function getTimeLimit() { return $this->timeLimit; }

	public function setTimeLimit($timeLimit) { $this->timeLimit = $timeLimit; }

	public function getResponse() { return $this->response; }

}

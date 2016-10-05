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


namespace modules\I18N\lib;

class I18NFormatterFactory
{
	var $context;
	var $formatters;
	
	function setContext(&$context)
	{
		$this->context = $context;
	}
	
	function doesFormatterExist($type)
	{
		if (is_null($this->formatters))
		{
			$this->createFormatters();
		}
		return isset($this->formatters[$type]);
	}

	function getIntFormatter()
	{
		return $this->getFormatter('integer');
	}
	
	function getFloatFormatter()
	{
		return $this->getFormatter('float');
	}
	
	function getDateFormatter()
	{
		return $this->getFormatter('date');
	}

	public function getDateTimeFormatter()
	{
		return $this->getFormatter('datetime');
	}

	function getNullFormatter()
	{
		if (!isset($this->nullFormatter))
		{
			$this->nullFormatter = new Formatters\NullFormatter();
		}
		return $this->nullFormatter;
	}
	
	function getFormatter($type)
	{
		if (is_null($this->formatters))
		{
			$this->createFormatters();
		}
		return $this->formatters[$type];
	}
	
	function createFormatters()
	{
		$this->formatters['integer'] = $this->createIntFormatter();
		$this->formatters['float'] = $this->createFloatFormatter();
		$this->formatters['date'] = $this->createDateFormatter();
		$this->formatters['datetime'] = $this->createDateTimeFormatter();
	}
	
	function createIntFormatter()
	{
		$thousands_separator = $this->context->getThousandsSeparator();
		if (is_null($thousands_separator))
		{
			\core\Logger::error('THOUSANDS SEPARATOR NOT SET');
			$formatter = $this->getNullFormatter();
		}
		else
		{
			$formatter = new Formatters\IntFormatter();
			$formatter->setThousandsSeparator($thousands_separator);
		}
		return $formatter;
	}

	
	function createFloatFormatter()
	{
		$thousands_separator = $this->context->getThousandsSeparator();
		$decimal_point = $this->context->getDecimalPoint();
		
		$errors = array();
		
		if (is_null($thousands_separator))
		{
			$errors[] = 'THOUSANDS SEPAPARATOR IS NULL';
		}
		
		if (empty($decimal_point))
		{
			$errors[] = 'DECIMALS POINT IS EMPTY';
		}
		
		if(count($errors) == 0)
		{
			$formatter = new Formatters\FloatFormatter();
			$formatter->setThousandsSeparator($thousands_separator);
			$formatter->setDecimalPoint($decimal_point);
		} 
		else 
		{
			$formatter = $this->getNullFormatter();
			foreach($errors as $error)
			{
				\core\Logger::error($error);
			}
		}
		
		return $formatter;
	}
	
	function createDateFormatter()
	{
		$date_format = $this->context->getDateFormat();
		if (empty($date_format))
		{
			\core\Logger::error('FORMAT IS EMPTY');
			$formatter = $this->getNullFormatter();
		}
		else
		{
			$formatter = new Formatters\DateFormatter();
			$formatter->setDateFormat($date_format);
		}
		return $formatter;
	}

	public function createDateTimeFormatter()
	{
		$dateFormat = $this->context->getDateFormat();
		if (empty($dateFormat))
		{
			$formatter = $this->getNullFormatter();
		}
		else
		{
			$formatter = new Formatters\DateTimeFormatter();
			$formatter->setDateFormat($dateFormat . ' %H:%M:%S');
		}
		return $formatter;
	}
}

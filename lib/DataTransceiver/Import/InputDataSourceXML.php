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


namespace lib\DataTransceiver\Import;

class InputDataSourceXML implements IInputDataSource
{
	const DELIMITER = '|'; // used to separate multiple XML tags, when converting to array

	private  $config;
	private $fieldsScheme;
	/**
	 * @var $xmlReader \XMLReader
	 */
	private $xmlReader;
	private $listingNodeName;
	private $isEmpty;

	public function getNext()
	{
		$xmlContent = $this->getArrayFromXML($this->xmlReader->readOuterXml());
		$this->xmlReader->next();
		do
		{
			if ($this->xmlReader->name === $this->listingNodeName && $this->xmlReader->nodeType == \XMLReader::ELEMENT)
			{
				break;
			}
			elseif ($this->xmlReader->nodeType == \XMLReader::END_ELEMENT)
			{
				$this->isEmpty = true;
				break;
			}
			$this->xmlReader->read();
		} while (1);
        if (!is_null($this->fieldsScheme))
        {
            $xmlContent = $this->getSchemedXMLContent($xmlContent);
        }
        return $xmlContent;
    }

    private function getSchemedXMLContent($xmlContent)
    {
        $schemedXmlContent = array();
        foreach ($this->fieldsScheme as $fieldID)
        {
            $schemedXmlContent[$fieldID] = isset($xmlContent[$fieldID]) ? $xmlContent[$fieldID] : null;
        }
        $xmlContent = $schemedXmlContent;
        return $xmlContent;
    }

    private function getArrayFromXML($xmlString)
	{
		$xml = simplexml_load_string($xmlString);

		$result = array();
		$this->getNodeValues($xml, "", $result);
		return $result;
	}

	private function getNodeValues($node, $pathToNode, &$result)
	{
		if ($node->count() == 0)
		{
			if (isset($result[$pathToNode]))
			{
				$result[$pathToNode] .= self::DELIMITER . (string) $node;
			}
			else
			{
				$result[$pathToNode] = (string) $node;
			}
		}
		else
		{
			foreach ($node->children() as $child)
			{
				$path = empty($pathToNode) ? $child->getName() : $pathToNode . '.' . $child->getName();
				$this->getNodeValues($child, $path, $result);
			}
		}
	}

	public function isEmpty()
	{
		return $this->isEmpty;
	}

	public function getFieldsScheme()
	{
		return $this->fieldsScheme;
	}

	public function init()
	{
		if (($filePath = \App()->FileSystem->downloadFileIfNotExistsOrModified($this->config->getFilePath(), $this->config->getLocalFileName())) === false)
		{
			throw new \lib\DataTransceiver\TransceiveFailedException('INVALID_FILE');
		}

		$this->xmlReader = $this->getPreparedXMLReader($filePath);
		$this->fieldsScheme = array_keys($this->getNext());
		$this->xmlReader->close();

		$this->xmlReader = $this->getPreparedXMLReader($filePath);
	}

	private function getPreparedXMLReader($filePath)
	{
		$xmlReader = new \XMLReader();
		if ($xmlReader->open($filePath) === false)
		{
			throw new \lib\DataTransceiver\TransceiveFailedException('INVALID_FILE');
		}

		$pathToListingNode = $this->config->getListingNode();

		$pathToListingNode = explode('.', $pathToListingNode);
		$this->listingNodeName = end($pathToListingNode);

		$level = 0;
		$xmlReader->read();

		do
		{
			if ($xmlReader->nodeType == \XMLReader::END_ELEMENT)
			{
				throw new \lib\DataTransceiver\TransceiveFailedException('INVALID_ROOT_NODE');
			}
			elseif ($xmlReader->nodeType == \XMLReader::ELEMENT)
			{
				if ($xmlReader->name === $pathToListingNode[$level])
				{
					$level++;
					if ($level >= sizeof($pathToListingNode))
					{
						$this->isEmpty = false;
						break;
					}
					$xmlReader->read();
				}
				else
				{
					$xmlReader->next();
				}
			}
			else
			{
				$xmlReader->read();
			}
		} while (1);

		return $xmlReader;
	}

	public function getCaption()
	{
		return "XML";
	}

	private function getAllowedFileExtensions()
	{
		return array('xml', 'rss');
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}

<?php

namespace danharper\JSONx;

use XMLWriter;

class ToJSONx {

	private $isRoot = true;

	/**
	 * @var XmlWriter
	 */
	private $writer;

	public function execute($input)
	{
		$this->isRoot = true;

		$this->writer = new XMLWriter;
		$this->writer->openMemory();
		$this->writer->startDocument('1.0', 'UTF-8');
		$this->writer->setIndent(true);

		$this->run($input);

		return $this->writer->flush();
	}

	private function run($value, $name = null)
	{
		$this->startJSONxElement($value, $name);

		if ($this->isObjectLike($value))
		{
			foreach ($value as $k => $v) $this->run($v, $k);
		}
		else if (is_array($value))
		{
			foreach ($value as $x) $this->run($x);
		}
		else if (is_string($value) || is_numeric($value) || is_null($value))
		{
			$this->writer->text($value);
		}
		else if (is_bool($value))
		{
			$this->writer->text($value ? 'true' : 'false');
		}

		$this->endJSONxElement();
	}

	private function startJSONxElement($value, $name)
	{
		$this->writer->startElementNs('json', $this->typeOf($value), null);

		if ($name)
		{
			$this->writer->writeAttribute('name', $name);
		}

		if ($this->isRoot)
		{
			$this->isRoot = false;
			$this->writeRootAttributes();
		}
	}

	private function endJSONxElement()
	{
		$this->writer->endElement();
	}

	private function isObjectLike($input)
	{
		return is_object($input) || $this->isAssociativeArray($input);
	}

	private function isAssociativeArray($input)
	{
		// http://stackoverflow.com/a/4254008/148975
		return is_array($input) && (bool) count(array_filter(array_keys($input), 'is_string'));
	}

	private function typeOf($input)
	{
		if ($this->isObjectLike($input)) return 'object';
		if (is_array($input)) return 'array';
		if (is_string($input)) return 'string';
		if (is_numeric($input)) return 'number';
		if (is_bool($input)) return 'boolean';
		if (is_null($input)) return 'null';
		throw new \InvalidArgumentException('Unrecognised Type for JSONx: "' . gettype($input) . '"');
	}

	private function writeRootAttributes()
	{
		$this->writer->writeAttribute('xmlns:json', 'http://www.ibm.com/xmlns/prod/2009/jsonx');
		$this->writer->writeAttribute('xsi:schemaLocation', 'http://www.datapower.com/schemas/json jsonx.xsd');
		$this->writer->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
	}

}
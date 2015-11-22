<?php

namespace danharper\JSONx;

use XMLReader;

class FromJSONx {

	const TO_IGNORE = [XMLReader::WHITESPACE, XMLReader::SIGNIFICANT_WHITESPACE, XMLReader::COMMENT];

	/**
	 * @var XMLReader|null
	 */
	private $reader;

	public function execute($input)
	{
		$this->reader = new XMLReader;

		$this->reader->XML($input);

		$this->reader->read();

		while (in_array($this->reader->nodeType, self::TO_IGNORE)) $this->reader->read();

		switch ($this->typeOf($this->reader->name))
		{
			case 'array': return $this->parseArray();
			case 'object': return $this->parseObject();
			default: throw new \InvalidArgumentException('JSONx must begin with an object or array node, given: "' . $this->reader->name . '"');
		}
	}

	private function parseArrayLike(ParsingArrayLike $builder)
	{
		$x = $builder;

		if ( ! $this->reader->isEmptyElement)
		{
			while ($this->reader->read() && $this->reader->nodeType !== XMLReader::END_ELEMENT)
			{
				if (in_array($this->reader->nodeType, self::TO_IGNORE)) continue;

				$x->add($this->reader->getAttribute('name'), $this->getNextValue());
			}
		}

		return $x->output();
	}

	private function getNextValue()
	{
		switch ($this->typeOf($this->reader->name))
		{
			case 'string': return $this->parseString();
			case 'number': return $this->parseNumber();
			case 'null': return null;
			case 'boolean': return $this->parseBoolean();
			case 'array': return $this->parseArray();
			case 'object': return $this->parseObject();
			default: throw new \InvalidArgumentException('Unknown JSONx Type: "' . $this->reader->name . '"');
		}
	}

	private function parseArray()
	{
		return $this->parseArrayLike(ParsingArrayLike::forArray());
	}

	private function parseObject()
	{
		return $this->parseArrayLike(ParsingArrayLike::forObject());
	}

	private function parseString()
	{
		return $this->parseText();
	}

	private function parseNumber()
	{
		return $this->intOrFloat($this->parseText());
	}

	private function intOrFloat($number)
	{
		return strpos($number, '.') === false ? (int) $number : (float) $number;
	}

	private function parseBoolean()
	{
		return $this->parseText() === 'true';
	}

	private function parseText()
	{
		$this->reader->read();
		$value = $this->reader->value;
		$this->reader->read();
		return $value;
	}

	private function typeOf($name)
	{
		if (strpos($name, 'json:') !== 0)
		{
			throw new \InvalidArgumentException('Unrecognised Node Name for JSONx: "' . $name . '"');
		}

		return substr($name, strlen('json:'));
	}

}
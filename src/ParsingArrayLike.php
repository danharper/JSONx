<?php

namespace danharper\JSONx;

class ParsingArrayLike {

	/**
	 * @var array
	 */
	private $pieces = [];

	/**
	 * @var bool
	 */
	private $isArray;

	private function __construct($isArray)
	{
		$this->isArray = (bool) $isArray;
	}

	public static function forArray()
	{
		return new self(true);
	}

	public static function forObject()
	{
		return new self(false);
	}

	public function add($key, $value)
	{
		if ($this->isArray)
		{
			$this->pieces[] = $value;
		}
		else
		{
			if ( ! $key) throw new \InvalidArgumentException('JSONx element in object does not contain a "name" attribute. The content is: "' . $value . '"');

			$this->pieces[$key] = $value;
		}
	}

	public function output()
	{
		return $this->isArray ? $this->pieces : (object) $this->pieces;
	}

}
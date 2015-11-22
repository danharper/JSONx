<?php

namespace danharper\JSONx;

class JSONx {

	/**
	 * @var ToJSONx
	 */
	private $toJSONx;

	/**
	 * @var FromJSONx
	 */
	private $fromJSONx;

	public function __construct(ToJSONx $toJSONx = null, FromJSONx $fromJSONx = null)
	{
		$this->toJSONx = $toJSONx ?: new ToJSONx;
		$this->fromJSONx = $fromJSONx ?: new FromJSONx;
	}

	public function toJSONx($json)
	{
		return $this->toJSONx->execute($json);
	}

	public function fromJSONx($xml)
	{
		return $this->fromJSONx->execute($xml);
	}

}
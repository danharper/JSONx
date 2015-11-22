<?php

namespace danharper\JSONx\Tests;

class TestCase extends \PHPUnit_Framework_TestCase {

	const XML_TAG = '<?xml version="1.0" encoding="UTF-8"?>';

	const ROOT_ATTRIBUTES = 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx" xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';

	protected function header($elementName)
	{
		return self::XML_TAG . '<json:' . $elementName . ' ' . self::ROOT_ATTRIBUTES . '>';
	}

	protected function footer($elementName)
	{
		return '</json:' . $elementName . '>' . PHP_EOL;
	}

	protected function emptyHeader($elementName)
	{
		return self::XML_TAG . '<json:' . $elementName . ' ' . self::ROOT_ATTRIBUTES . '/>';
	}

	protected function within($elementName, $child)
	{
		return $this->header($elementName) . $child . $this->footer($elementName);
	}

}
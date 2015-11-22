<?php

namespace danharper\JSONx\Tests;

use danharper\JSONx\ToJSONx;

class ToJSONxTest extends TestCase {

	use SharedTestsTrait;

	// assoc array -> JSON or JSONx becomes a object
	// from JSON or JSONx has no way to become assoc instead of object
	public function testAssocArrayWithValueIsSameAsObject()
	{
		$expected = $this->header('object') . '<json:string name="Ticker">IBM</json:string>' . $this->footer('object');

		$this->assertXml($expected, ['Ticker' => 'IBM']);
	}

	public function testAssocArrayWithMixedValuesIsSameAsObject()
	{
		$expected = $this->header('object') . '<json:string name="Ticker">IBM</json:string><json:array name="foo"><json:string>102</json:string><json:null /></json:array>' . $this->footer('object');

		$this->assertXml($expected, ['Ticker' => 'IBM', 'foo' => ['102', null]]);
	}

	public function testClassDoesntHoldStateAndCanBeUsedMultipleTimes()
	{
		$inst = new ToJSONx;

		$inst->execute([]); // execute once so that any state is initialised

		$this->assertXmlStringEqualsXmlString($this->emptyHeader('array'), $inst->execute([]));
	}

	private function assertXml($expected, $input)
	{
		$this->assertXmlStringEqualsXmlString($expected, (new ToJSONx)->execute($input));
	}

}
<?php

namespace danharper\JSONx\Tests;

use danharper\JSONx\FromJSONx;

class FromJSONxTest extends TestCase {

	use SharedTestsTrait;

	public function testXmlWithWhitespace()
	{
		$expected = ['foo'];

		$input = $this->header('array') . '   <json:string>foo</json:string>  ' . $this->footer('array');

		$this->assertXml($input, $expected);
	}

	public function testXmlWithComments()
	{
		$expected = ['foo'];

		$input = $this->header('array') . '  <!-- foo --> <json:string>foo</json:string>  ' . $this->footer('array');

		$this->assertXml($input, $expected);
	}

	public function testClassDoesntHoldStateAndCanBeUsedMultipleTimes()
	{
		$inst = new FromJSONx;

		$inst->execute($this->emptyHeader('array')); // execute once so that any state is initialised

		$this->assertEquals([], $inst->execute($this->emptyHeader('array')));
	}

	protected function assertXml($input, $expected)
	{
		$this->assertEquals($expected, (new FromJSONx)->execute($input));
	}

}
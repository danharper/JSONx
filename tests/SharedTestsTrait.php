<?php

namespace danharper\JSONx\Tests;

trait SharedTestsTrait {

	public function testEmptyArray()
	{
		$this->assertXml($this->emptyHeader('array'), []);
	}

	public function testEmptyObject()
	{
		$this->assertXml($this->emptyHeader('object'), (object) []);
	}

	public function testArrayWithString()
	{
		$expected = $this->header('array') . '<json:string>Foo</json:string>' . $this->footer('array');

		$this->assertXml($expected, ['Foo']);
	}

	public function testArrayWithInt()
	{
		$expected = $this->header('array') . '<json:number>1</json:number>' . $this->footer('array');

		$this->assertXml($expected, [1]);
	}

	public function testArrayWithFloat()
	{
		$expected = $this->header('array') . '<json:number>3.14</json:number>' . $this->footer('array');

		$this->assertXml($expected, [3.14]);
	}

	public function testArrayWithNull()
	{
		$expected = $this->header('array') . '<json:null />' . $this->footer('array');

		$this->assertXml($expected, [null]);
	}

	public function testArrayWithBools()
	{
		$expected = $this->header('array') . '<json:boolean>true</json:boolean><json:boolean>false</json:boolean><json:boolean>true</json:boolean>' . $this->footer('array');

		$this->assertXml($expected, [true, false, true]);
	}

	public function testNestedArray()
	{
		$expected = $this->header('array') . '<json:array></json:array><json:array><json:string>OMG LOL</json:string></json:array>' . $this->footer('array');

		$this->assertXml($expected, [[], ['OMG LOL']]);
	}

	public function testMixedArray()
	{
		$expected = $this->header('array') . '<json:boolean>true</json:boolean><json:boolean>false</json:boolean><json:boolean>true</json:boolean><json:string>foo</json:string><json:number>3.14</json:number><json:null /><json:array></json:array><json:array><json:string>OMG LOL</json:string></json:array>' . $this->footer('array');

		$this->assertXml($expected, [true, false, true, 'foo', 3.14, null, [], ['OMG LOL']]);
	}

	public function testObjectWithValue()
	{
		$expected = $this->header('object') . '<json:string name="Ticker">IBM</json:string>' . $this->footer('object');

		$this->assertXml($expected, (object) ['Ticker' => 'IBM']);
	}

	public function testObjectWithMixedValues()
	{
		$expected = $this->header('object') . '<json:string name="Ticker">IBM</json:string><json:array name="foo"><json:string>102</json:string><json:null /></json:array>' . $this->footer('object');

		$this->assertXml($expected, (object) ['Ticker' => 'IBM', 'foo' => ['102', null]]);
	}

	public function testJsonDecoded()
	{
		$expected = $this->header('object') . '<json:string name="Ticker">IBM</json:string><json:array name="foo"><json:string>102</json:string><json:null /></json:array>' . $this->footer('object');

		$this->assertXml($expected, json_decode('{"Ticker": "IBM", "foo": ["102", null]}'));
	}

	public function testInnerEmptyArray()
	{
		$xml = $this->header('array') . '<json:array />' . $this->footer('array');

		$this->assertXml($xml, [[]]);
	}

	public function testInnerEmptyObject()
	{
		$xml = $this->header('array') . '<json:object />' . $this->footer('array');

		$this->assertXml($xml, [(object) []]);
	}

	public function testEmptyArraysBecomeClosed()
	{
		$xml = $this->header('array') . '<json:array/><json:array/>' . $this->footer('array');

		$json = [[], []];

		$this->assertXml($xml, $json);
	}

	public function testEmptyObjectsBecomeClosed()
	{
		$xml = $this->header('array') . '<json:object/><json:array/>' . $this->footer('array');

		$json = [
			(object) [],
			[],
		];

		$this->assertXml($xml, $json);
	}

	public function testLoads()
	{
		$input = <<<JSON
{
	"data": [
		{
			"id": 1,
			"name": "Dan Harper",
			"favPi": 3.14,
			"favFruits": ["apple", "pear", "mango", null, {"this": "is something weird", "with": ["an array"]}]
		},
		{
			"id": 2,
			"name": "Bob Jones",
			"favPi": 3,
			"favFruits": {}
		}
	],
	"pagination": {
		"currentPage": 1,
		"totalPages": 10,
		"perPage": 2
	}
}
JSON;

		$expected = <<<XML
<json:array name="data">
	<json:object>
		<json:number name="id">1</json:number>
		<json:string name="name">Dan Harper</json:string>
		<json:number name="favPi">3.14</json:number>
		<json:array name="favFruits">
			<json:string>apple</json:string>
			<json:string>pear</json:string>
			<json:string>mango</json:string>
			<json:null/>
			<json:object>
				<json:string name="this">is something weird</json:string>
				<json:array name="with">
					<json:string>an array</json:string>
				</json:array>
			</json:object>
		</json:array>
	</json:object>
	<json:object>
		<json:number name="id">2</json:number>
		<json:string name="name">Bob Jones</json:string>
		<json:number name="favPi">3</json:number>
		<json:object name="favFruits" />
	</json:object>
</json:array>
<json:object name="pagination">
	<json:number name="currentPage">1</json:number>
	<json:number name="totalPages">10</json:number>
	<json:number name="perPage">2</json:number>
</json:object>
XML;

		$expected = $this->within('object', $expected);

		$this->assertXml($expected, json_decode($input));
	}

}
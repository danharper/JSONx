<?php

namespace danharper\JSONx\Tests;

use danharper\JSONx\FromJSONx;
use danharper\JSONx\ToJSONx;

class BackAndForthTest extends TestCase {

	public function testIt()
	{
		$xml = <<<XML
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

		$xml = $this->within('object', $xml);

		$parsedFromXml = (new FromJSONx)->execute($xml);

		$parsedToJson = json_encode($parsedFromXml);

		$parsedFromJson = json_decode($parsedToJson);

		$parsedToXml = (new ToJSONx)->execute($parsedFromJson);

		$this->assertXmlStringEqualsXmlString($xml, $parsedToXml);
	}

}
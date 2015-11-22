# JSONx

A library implementing IBM's standard format for [representing JSON as XML](http://www-01.ibm.com/support/knowledgecenter/SS9H2Y_6.0.0/com.ibm.dp.xm.doc/json_jsonx.html).

Supports both reading & writing of JSONx.

I've previously [joked about it](https://twitter.com/danharper7/status/514822464673951744) then a year later found myself needing it to integrate our initially JSON APIs with enterprise systems only using XML.

The draft spec can be found at:

http://tools.ietf.org/html/draft-rsalz-jsonx-00

## Installation

```
composer require danharper/jsonx
```

## Usage

```php
$jsonx = new danharper\JSONx;

$jsonx->toJSONx([
  'foo' => [ 'bar', true, 2, 3.14, null, [], (object) [] ]
]);

/*
<?xml version="1.0" encoding="UTF-8"?>
<json:object xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx" xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <json:array name="foo">
    <json:string>bar</json:string>
    <json:boolean>true</json:boolean>
    <json:number>2</json:number>
    <json:number>3.14</json:number>
    <json:null/>
    <json:array/>
    <json:object/>
  </json:array>
</json:object>
*/

$jsonx->fromJSONx('
<?xml version="1.0" encoding="UTF-8"?>
<json:object xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx" xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <json:array name="foo">
    <json:string>bar</json:string>
    <json:boolean>true</json:boolean>
    <json:number>2</json:number>
    <json:number>3.14</json:number>
    <json:null/>
    <json:array/>
    <json:object/>
  </json:array>
</json:object>
');

/*
{
  "foo": [ "bar", true, 2, 3.14, null, [], {} ]
}
*/
```

